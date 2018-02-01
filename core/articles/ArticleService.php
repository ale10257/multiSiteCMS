<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 9:15
 */

namespace app\core\articles;

use app\components\helpers\phpjquery\PhpJqueryHelper;
use app\core\articles\forms\ArticleForm;
use app\core\articles\forms\ArticleImageForm;
use app\core\articles\repositories\ArticleImagesRepository;
use app\core\articles\repositories\ArticleRepository;
use app\core\categories\CacheCategory;
use app\core\categories\CategoryRepository;
use app\core\workWithFiles\helpers\ChangeDirectory;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\core\workWithFiles\helpers\DeleteImages;
use app\core\workWithFiles\helpers\RemoveDirectory;

class ArticleService
{
    /** @var ArticleForm  */
    private $articleForm;
    /** @var ArticleImageForm  */
    private $imageForm;
    /** @var ArticleRepository  */
    private $articleRepository;
    /** @var ArticleImagesRepository  */
    private $imageRepositary;
    /** @var CacheCategory  */
    private $cacheCategory;

    /**
     * ArticleService constructor.
     * @param CacheCategory $cacheCategory
     */
    public function __construct(CacheCategory $cacheCategory)
    {
        $this->articleForm = new ArticleForm();
        $this->imageForm = new ArticleImageForm();
        $this->articleRepository = new ArticleRepository();
        $this->imageRepositary = new ArticleImagesRepository();
        $this->cacheCategory = $cacheCategory;
    }

    /**
     * @param ArticleForm $form
     * @return int
     */
    public function create(ArticleForm $form)
    {
        $form->alias = Inflector::slug($form->name);
        $this->articleRepository->insertValues($form, true);
        $this->articleRepository->saveItem();

        return $this->articleRepository->id;
    }

    /**
     * @param ArticleForm $form
     * @param int $id
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function update($form, int $id)
    {
        $article = $this->articleRepository->getItem($id);
        $oldCategory = $article->categories_id;
        $oldText = $article->text;
        $web_dir = $article->getWebDir();

        if ($article->image) {
            if ($mainImg = $form->uploadOneFile($web_dir, 'one_image')) {
                DeleteImages::deleteImages($web_dir, $article->image);
                $form->image = $mainImg;
            }
        } else {
            $form->image = $form->uploadOneFile($web_dir, 'one_image');
        }

        $form->text = PhpJqueryHelper::changeImages($form->text, $article->getWebDir());
        PhpJqueryHelper::deleteImagesFromFS($oldText, $form->text, $article->getWebDir());
        $article->insertValues($form);
        $article->saveItem();

        if ($images = $form->uploadAnyFile($web_dir, 'any_images')) {
            $sort = $this->imageRepositary->getNumLastElement(['articles_id' => $article->id], 'sort');
            foreach ($images as $image) {
                $img = new ArticleImagesRepository();
                $img->name = $image;
                $img->articles_id = $article->id;
                $img->sort = $sort;
                $img->saveItem();
                $sort++;
            }
        }

        $newCategory = $article->categories_id;

        if ($oldCategory != $newCategory) {
            ChangeDirectory::changeDirectory($web_dir, $article);
        }
    }

    public function getNewForm()
    {
        $form = $this->articleForm;
        $form->categories = $this->cacheCategory->getLeavesCategory(CategoryRepository::RESERVED_TYPE_ARTICLE);

        return $form;
    }

    /**
     * @param int $id
     * @return ArticleForm
     * @throws NotFoundHttpException
     */
    public function getUpdateForm(int $id)
    {
        if (!$article = $this->articleRepository::find()
            ->where(['id' => $id])
            ->with('category')
            ->with([
                'images' => function ($q) {
                    /**@var \yii\db\ActiveQuery $q */
                    $q->orderBy(['sort' => SORT_ASC]);
                }
            ])
            ->one()) {
            throw new NotFoundHttpException();
        }
        $this->articleForm->createUpdateForm($article);
        $this->articleForm->link = $article->category->multiple ? Url::to(['/article/' . $article->alias]) : Url::to(['/article/' . $article->category->alias]) ;
        $this->articleForm->categories = $this->getLeavesCategories();
        $this->articleForm->webDir = $article->getWebDir();
        if ($article->images) {
            foreach ($article->images as $image) {
                $img = new ArticleImageForm();
                $img->createUpdateForm($image);
                $this->articleForm->uploaded_images[] = $img;
            }
        }

        return $this->articleForm;
    }

    /**
     * @param $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete($id)
    {
        $this->articleRepository = $this->articleRepository->getItem($id);
        $this->articleRepository->deleteItem();
        RemoveDirectory::removeDirectory($this->articleRepository->getWebDir());
    }

    /**
     * @param int $id
     * @param int|null $status
     */
    public function changeActive(int $id, int $status = null)
    {
        $this->articleRepository->changeActive($id, $status);
    }

    /**
     * @param $id
     * @return bool
     * @throws \yii\db\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function deleteMainImage($id): bool
    {
        $article = $this->articleRepository->getItem($id);
        if ($image = $article->image) {
            $article->image = '';
            $article->updateField('image');
            DeleteImages::deleteImages($article->getWebDir(), $image);
        }

        return true;
    }

    /**
     * @return CategoryRepository[]
     */
    public function getLeavesCategories()
    {
        return $this->cacheCategory->getLeavesCategory(CategoryRepository::RESERVED_TYPE_ARTICLE);
    }
}