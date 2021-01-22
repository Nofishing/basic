<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

//高逼格打印函数
if (!function_exists('dd')) {
    function dd($var){
        $content = \yii\helpers\VarDumper::dumpAsString($var, 10, true);
        return die($content);
    }
}

(new yii\web\Application($config))->run();
