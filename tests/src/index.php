<?php
$app_path = defined("APP_PATH") ? APP_PATH : "./../";
$configPath = defined("CONFIG_PATH") ? CONFIG_PATH : "./../config/";
error_reporting(E_ALL);
$loader = require __DIR__ . './../../vendor/autoload.php';
$App = yuxblank\phackp\core\Application::getInstance();
$App->bootstrap($app_path,$configPath);
/*$App->registerService(ErrorHandlerProvider::class,true);*/
$App->run();


