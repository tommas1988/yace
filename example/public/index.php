<?php
define('APP_PATH', __DIR__ . '/../');
$app = new Yaf_Application(APP_PATH . 'config/application.ini');
$app->bootstrap()->run();
