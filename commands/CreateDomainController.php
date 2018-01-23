<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17.12.17
 * Time: 17:53
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\FileHelper;
use yii;

class CreateDomainController extends Controller
{
    /**
     * @throws yii\base\Exception
     */
    public function actionIndex(): void
    {
        $site_constant = $this->prompt('Enter SITE CONSTANT', ['required' => true]);
        $app_name = $this->prompt('Enter Application Name', ['required' => true]);

        $startFolder = yii::getAlias('@app/sites/startSite');
        $startFolderConfig = yii::getAlias('@app/config/startSite');
        $startFolderWeb = yii::getAlias('@app/web/startSite');

        $newFolder = str_replace('startSite', $site_constant, $startFolder);
        $newFolderConfig = str_replace('startSite', $site_constant, $startFolderConfig);
        $newFolderWeb = str_replace('startSite', $site_constant, $startFolderWeb);

        FileHelper::copyDirectory($startFolder, $newFolder);
        FileHelper::copyDirectory($startFolderConfig, $newFolderConfig);
        FileHelper::copyDirectory($startFolderWeb, $newFolderWeb);

        $indexFilePath = $newFolderWeb . '/index.php';
        $indexFile = str_replace('startSite', $site_constant, file_get_contents($indexFilePath));
        file_put_contents($indexFilePath, $indexFile);

        $controllerFilePath = $newFolder . '/BaseController.php';
        $controllerFile = str_replace('startSite', $site_constant, file_get_contents($controllerFilePath));
        file_put_contents($controllerFilePath, $controllerFile);

        $controllerFilePath = $newFolder . '/controllers/SiteController.php';
        $controllerFile = str_replace('startSite', $site_constant, file_get_contents($controllerFilePath));
        file_put_contents($controllerFilePath, $controllerFile);

        $configFilePath = $newFolderConfig . '/web.php';
        $configFile = str_replace(['startSiteKey', 'startSite'], [yii::$app->security->generateRandomString(), $site_constant], file_get_contents($configFilePath));
        file_put_contents($configFilePath, $configFile);


        $paramsFilePath = $newFolderConfig . '/params.php';
        $paramsFile = str_replace('startSite', $app_name, file_get_contents($paramsFilePath));
        file_put_contents($paramsFilePath, $paramsFile);

    }
}
