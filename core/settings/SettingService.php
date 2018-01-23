<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22.12.17
 * Time: 22:51
 */

namespace app\core\settings;

use app\core\cache\CacheEntity;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\Request;

class SettingService
{
    /**@var SettingRepository */
    private $_repository;

    /** @var CacheEntity */
    private $_cache;

    /** @var \app\core\settings\SettingForm  */
    private $_form;

    /**
     * ServiceSetting constructor.
     * @param CacheEntity $cache
     */
    public function __construct(CacheEntity $cache)
    {
        $this->_repository = new SettingRepository;
        $this->_form = new SettingForm;
        $this->_cache = $cache;
    }

    /**
     * @param SettingForm $form
     * @param int|null $parent_id
     * @throws \yii\web\NotFoundHttpException
     */
    public function create(SettingForm $form, int $parent_id = null)
    {
        if (empty($form->alias)) {
            $form->alias = Inflector::slug($form->name);
        }
        $this->_repository->insertValues($form);
        $parent = $parent_id === null ? $this->_repository->getRoot() : $this->_repository->getItem($parent_id);
        if (!$parent) {
            throw new \DomainException('Parent category not found!');
        }
        if ($parent_id == null) {
            /**@var $parent SettingRepository */
            $this->_repository->checkUniqAlias($form->alias, $parent->tree);
        }
        $this->_repository->prependTo($parent);
        $this->_cache->deleteItem($this->_cache::SETTING_TREE);
    }

    /**
     * @param SettingForm $form
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(SettingForm $form, int $id)
    {
        $setting = $this->_repository->getItem($id);
        if (empty($form->alias)) {
            $form->alias = Inflector::slug($form->name);
        }
        $setting->insertValues($form);
        if ($setting->depth == 1) {
            /**@var $parent SettingRepository */
            $setting->checkUniqAlias();
        }
        $setting->saveItem();
        if ($setting->alias == ReservedSettings::LOGIN_EMAIL || $setting->alias == ReservedSettings::PASSWD_EMAIL || $setting->alias == ReservedSettings::MAIL_ADMIN) {
            $loginEmail = $this->_repository->findOne(['alias' => ReservedSettings::LOGIN_EMAIL]);
            $passwdEmail = $this->_repository->findOne(['alias' => ReservedSettings::PASSWD_EMAIL]);
            $adminEmail = $this->_repository->findOne(['alias' => ReservedSettings::MAIL_ADMIN]);
            $path = yii::getAlias('@app/config/') . SITE_ROOT_NAME . '/data_email.php';
            $str = '<?php
return [
    "adminEmail" => "' . $adminEmail->value . '",
    "loginEmail" => "' . $loginEmail->value . '",
    "passwdEmail" => "' . $passwdEmail->value . '",
];
';
            file_put_contents($path, $str);
        }
        $this->_cache->deleteItem($this->_cache::SETTING_TREE);
    }

    /**
     * @param int|null $parent_id
     * @param int|null $id
     * @return SettingForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getForm(int $parent_id = null, int $id = null)
    {
        if ($id === null) {
            if ($parent_id) {
                $parent = $this->_repository->getItem($parent_id);
                if ($parent->depth == 2) {
                    throw new \DomainException('Нельзя создавать детей 3-го уровня!');
                }
            }
            return $this->_form;
        }

        if ($parent_id != -1) {
            throw new \DomainException('Parent id not found. For update category parent_id must bee = -1');
        }

        $setting = $this->_repository->getItem($id);
        $this->_form->createUpdateForm($setting);
        $this->_form->reserved = $this->getReservedFields();

        return $this->_form;
    }

    /**
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function delete(int $id)
    {
        $setting = $this->_repository->getItem($id);

        if (array_key_exists($setting->alias, $this->getReservedFields())) {
            throw new \DomainException('Нельзя удалять зарезервированные настройки');
        }
        $setting->deleteWithChildren();
        $this->_cache->deleteItem($this->_cache::SETTING_TREE);
    }

    /**
     * @return \ale10257\ext\ChangeTreeBehavior
     */
    public function getTree()
    {
        if (!$this->_repository->checkRoot()) {
            $this->_repository::createRoot();
        }

        if (!$tree = $this->_cache->getItem($this->_cache::SETTING_TREE)) {
            $this->_cache->setItem($this->_cache::SETTING_TREE, $this->_repository->getTree());
            $tree = $this->_cache->getItem($this->_cache::SETTING_TREE);
        }

        return $tree;
    }

    /**
     * @param Request $post
     * @return \ale10257\ext\ChangeTreeBehavior
     */
    public function updateTree($post)
    {
        $this->_repository->updateTree($post);
        $this->_cache->deleteItem($this->_cache::SETTING_TREE);
        return $this->_repository->getTree();
    }

    /**
     * @return mixed
     */
    private function getReservedFields()
    {
        foreach (ReservedSettings::RESERVED_SETTINGS as $item) {
            $reserved[$item['alias']] = $item['alias'];
            foreach ($item['childs'] as $child) {
                $reserved[$child['alias']] = $child['alias'];
            }
        }
        return $reserved;
    }
}