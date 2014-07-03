<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Logd\Core\App\Assets as AssetsLoader;
$request = Request::createFromGlobals();
$assetsLoader = new AssetsLoader(__DIR__.'/templates/bootstrap/assets');
$response = $assetsLoader->load($request);
$response->sendHeaders();
$response->sendContent();
