<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 14:07
 */

namespace app\core\categories;

use app\core\cache\CacheEntity;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use app\core\workWithFiles\helpers\DeleteImages;
use app\core\workWithFiles\helpers\RemoveDirectory;

class CategoryService implements \app\core\interfaces\CategoryService
{

    /**
     * @var CacheEntity
     */
    private $_cache;
    private $_form;
    private $_repository;

    /**
     * ServiceCategory constructor.
     * @param CacheEntity $cache
     */
    public function __construct(CacheEntity $cache)
    {
        $this->_form = new CategoryForm();
        $this->_repository = new CategoryRepository();
        $this->_cache = $cache;
        $this->checkRoot();
    }

    public function index()
    {
        if (!$tree = $this->_repository->getTree()) {
            $this->_repository::createRoot();
            $tree = $this->_repository->getTree();
        }
        return $tree;
    }

    /**
     * @param CategoryForm $form
     * @param int $parent_id
     * @throws \yii\web\NotFoundHttpException
     */
    public function create($form, $parent_id)
    {
        if (empty($form->alias)) {
            $form->alias = Inflector::slug($form->name);
        }

        $this->_repository->insertValues($form);

        /**@var $parent CategoryRepository */
        if ($parent_id === null) {
            $parent = $this->_repository->getRoot();
        } else {
            $parent = $this->_repository->getItem($parent_id);
        }

        if (!$parent) {
            throw new \DomainException('Parent category not found!');
        }

        $this->_repository->checkUniqAlias($this->_repository->alias, $parent->tree);
        $this->_repository->prependTo($parent);
        $this->_cache->deleteItem($this->_cache::CATEGORY_CACHE);
        $web_dir = $this->_repository->getWebDir();
        $this->_repository->image = $form->uploadOneFile($web_dir, 'one_image');

        if ($this->_repository->image) {
            $this->_repository->updateField('image');
        }
    }

    /**
     * @param CategoryForm $form
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function update($form, int $id)
    {
        $category = $this->_repository->getItem($id);
        $oldActive =  $category->active;
        $newActive = $form->active;
        $category->insertValues($form);
        $category->checkUniqAlias();
        $web_dir = $category->getWebDir();

        if ($image = $form->uploadOneFile($web_dir, 'one_image')) {
            if ($category->image) {
                DeleteImages::deleteImages($web_dir, $category->image);
            }
            $category->image = $image;
        }

        $category->saveItem();

        if ($oldActive != $newActive) {
            if ($childs = $category->children()->all()) {
                foreach ($childs as $child) {
                    /** @var CategoryRepository $child */
                    $child->active = $newActive;
                    $child->saveItem();
                }
            }
        }
        $this->_cache->deleteItem($this->_cache::CATEGORY_CACHE);
    }

    /**
     * @param int $parent_id
     * @return CategoryForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getNewForm(int $parent_id = null)
    {
        $form = $this->_form;
        if ($parent_id === null) {
            $form->parent = 'Нет родителя';
            $form->type_category_array = ArrayHelper::map($this->_repository::TYPE_CATEGORY, 'type', 'name');
        } else {
            $parent = $this->_repository->getItem($parent_id);
            $form->type_category = $parent->type_category;
            $form->type_category = $this->_repository::TYPE_CATEGORY[$parent->type_category]['type'];
            $form->name_type_category = $this->_repository::TYPE_CATEGORY[$parent->type_category]['name'];
            $form->parent = $parent->name;
        }

        return $form;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpdateForm(int $id)
    {
        /** @var CategoryRepository $category */
        $category = $this->_repository->getItem($id);

        if ($category->depth == 1) {
            $this->_form->parent = 'Нет родителя';
        } else {
            $parent = $category->parents(1)->one();
            if (!$parent) {
                throw new \DomainException('Parent category not found!');
            }
            $this->_form->parent = $parent->name;
        }

        $this->_form->createUpdateForm($category);

        $this->_form->name_type_category = $this->_repository::TYPE_CATEGORY[$this->_form->type_category]['name'];

        if ($category->image) {
            $this->_form->web_img = $category->getWebDir() . $category->image;
        }

        return $this->_form;
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function delete(int $id)
    {
        $category = $this->_repository->getItem($id);

        if ($category->depth == 1) {
            foreach ($this->_repository::TYPE_CATEGORY as $key => $item) {
                if ($key !== $this->_repository::RESERVED_TYPE_NO) {
                    if ($category->alias == $item['alias']) {
                        throw new \DomainException('Нельзя удалить зарезервированную родительскую категорию!');
                    }
                }
            }
        }

        /**@var CategoryRepository[] $categories */
        $categories = $category->children()->all();
        $categories[] = $category;

        foreach ($categories as $item) {
            $item->deleteItem();
            RemoveDirectory::removeDirectory($item->getWebDir());
        }

        $this->_cache->deleteItem($this->_cache::CATEGORY_CACHE);
    }

    /**
     * @param $post
     * @return \ale10257\ext\ChangeTreeBehavior
     */
    public function updateTree($post)
    {
        $this->_repository->updateTree($post);
        $this->_cache->deleteItem($this->_cache::CATEGORY_CACHE);
        return $this->_repository->getTree();
    }

    /**
     * @param $id
     * @return bool
     * @throws \yii\web\NotFoundHttpException
     */
    public function deleteImage($id): bool
    {
        $category = $this->_repository->getItem($id);
        if ($img = $category->image) {
            $category->image = '';
            $category->updateField('image');
            $dir = $category->getWebDir();
            DeleteImages::deleteImages($dir, $img);
        }
        return true;
    }

    private function checkRoot() : void
    {
        if (!CategoryRepository::find()->where(['name' => SITE_ROOT_NAME])->count()) {
            CategoryRepository::createRoot();
        }
    }

}