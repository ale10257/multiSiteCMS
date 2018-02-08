<?php

namespace app\modules\filemanager\models;

use Yii;
use app\modules\filemanager\SimpleFilemanagerModule;
use yii\base\InvalidParamException;

/**
 * Class Directory
 * @package app\modules\filemanager\models
 * @property array $list
 * @property boolean $isRoot
 * @property Directory|null $parent
 * @property array $breadcrumbs
 */
class Directory extends Item
{
    /**
     * @return Directory|null
     */
    public function getParent()
    {
        if ($this->isRoot) {
            return null;
        }

        $directoriesList = explode(DIRECTORY_SEPARATOR, $this->path);

        array_pop($directoriesList);

        $path = implode(DIRECTORY_SEPARATOR, $directoriesList);

        if (substr($path, 0, 1) != DIRECTORY_SEPARATOR) {
            $path = DIRECTORY_SEPARATOR . $path;
        }

        return new Directory([
            'root' => $this->root,
            'path' => $path
        ]);
    }

    /**
     * @param bool $deactivateLast
     * @return array
     */
    public function getBreadcrumbs($deactivateLast = true)
    {
        $breadcrumbs[] = [
            'label' => Yii::t('filemanager', 'File manager'),
            'url' => ['default/index']
        ];

        if ($this->isRoot) {
            return $breadcrumbs;
        }

        $directoriesList = explode(DIRECTORY_SEPARATOR, $this->path);

        $currentPath = '';

        foreach ($directoriesList as $n => $directory) {
            if (!$directory) {
                continue;
            }

            if (!$deactivateLast || $n < count($directoriesList) - 1) {
                $currentPath .= DIRECTORY_SEPARATOR . $directory;

                $breadcrumbs[] = [
                    'label' => $directory,
                    'url' => ['default/index', 'path' => $currentPath]
                ];
            } else {
                $breadcrumbs[] = $directory;
            }
        }

        return $breadcrumbs;
    }

    /**
     * @return bool
     */
    public function getIsRoot()
    {
        return $this->path === DIRECTORY_SEPARATOR;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return SimpleFilemanagerModule::getInstance()->icons['dir'];
    }

    /**
     * @return array
     */
    public function getList()
    {
        $path = $this->fullPath;

        if (substr($path, -1) != DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }

        if (!is_dir($path)) {
            throw new InvalidParamException();
        }

        $items = glob($path . '*');

        $result = [];

        if (count($items)) {

            if ($directories = array_filter($items, 'is_dir')) {
                $directories = array_map(function ($directory) {
                    return [
                        'path' => str_replace($this->root, '', $directory),
                        'icon' => $this->getIcon(),
                        'name' => basename($directory),
                        'type' => 'directory',
                        'time' => filectime($directory),
                    ];
                }, $directories);
            }

            if ($files = array_filter($items, 'is_file')) {
                $fileObject = new File(['root' => $this->root]);
                $files = array_map(function ($file) use ($fileObject) {
                    $fileObject->path = str_replace($this->root, '', $file);
                    return [
                        'url' => $fileObject->url,
                        'icon' => $fileObject->getIcon(),
                        'name' => basename($file),
                        'type' => 'file',
                        'path' => $fileObject->path,
                        'time' => filectime($file),
                    ];
                }, $files);
            }

            $result = array_merge($directories, $files);
        }

        if (!$this->isRoot) {
            array_unshift($result, [
                'name' => '..',
                'path' => $this->parent->path,
                'icon' => 'fa-level-up',
                'type' => 'directory',
            ]);
        }

        return $result;
    }

    /**
     * @param string $path
     *
     * @return Directory
     */
    public static function createByPath($path)
    {
        $directory = new self();
        $directory->root = SimpleFilemanagerModule::getInstance()->fullUploadPath;

        if ($path) {
            if (substr($path, 0, 1) != DIRECTORY_SEPARATOR) {
                $path = DIRECTORY_SEPARATOR . $path;
            }

            $directory->path = $path;
        }

        return $directory;
    }
}