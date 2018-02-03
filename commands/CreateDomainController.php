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

        $app = FileHelper::normalizePath(yii::getAlias('@app'));

        $startFolder = $app . DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR . 'startSite';
        $startFolderConfig = str_replace('sites', 'config', $startFolder);
        $startFolderWeb = str_replace('sites', 'web', $startFolder);

        $newFolder = str_replace('startSite', $site_constant, $startFolder);
        $newFolderConfig = str_replace('startSite', $site_constant, $startFolderConfig);
        $newFolderWeb = str_replace('startSite', $site_constant, $startFolderWeb);

        FileHelper::copyDirectory($startFolder, $newFolder);
        FileHelper::copyDirectory($startFolderConfig, $newFolderConfig);
        FileHelper::copyDirectory($startFolderWeb, $newFolderWeb);

        $indexFilePath = $newFolderWeb . DIRECTORY_SEPARATOR . 'index.php';
        $indexFile = str_replace('startSite', $site_constant, file_get_contents($indexFilePath));
        file_put_contents($indexFilePath, $indexFile);

        $controllerFilePath = $newFolder . DIRECTORY_SEPARATOR . 'BaseController.php';
        $controllerFile = str_replace('startSite', $site_constant, file_get_contents($controllerFilePath));
        file_put_contents($controllerFilePath, $controllerFile);

        $controllers = FileHelper::findFiles($newFolder . DIRECTORY_SEPARATOR . 'controllers', ['only' => ['*.php',]]);

        foreach ($controllers as $controller) {
            $controllerFile = str_replace('startSite', $site_constant, file_get_contents($controller));
            file_put_contents($controller, $controllerFile);
        }

        $configFilePath = $newFolderConfig . DIRECTORY_SEPARATOR . 'web.php';

        $configFile = str_replace(['startSite', 'startSite'], [yii::$app->security->generateRandomString(), $site_constant], file_get_contents($configFilePath));
        file_put_contents($configFilePath, $configFile);
        $appNamePath = $newFolderConfig . DIRECTORY_SEPARATOR . 'app_name.php';
        $str = '<?php
return [
    "name" => "' . $app_name . '",
];
';
        file_put_contents($appNamePath, $str);
    }
}
