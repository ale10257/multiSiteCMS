<?php
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

define('SITE_ROOT_NAME', 'startSite');
define('UPLOAD_DIR', 'uploads');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../config/' . SITE_ROOT_NAME . '/web.php';

(new yii\web\Application($config))->run();
