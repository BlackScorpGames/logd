<?php
require_once __DIR__ . '/vendor/autoload.php';
use Logd\Core\Env;

if (!Env::isTest()) {
    require 'dbconnect.php';

    $app = new Pimple\Container();

    $app['db'] = function ($app) use ($DB_HOST, $DB_NAME,$DB_USER, $DB_PASS) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $DB_HOST, $DB_NAME);
        $pdo = new PDO($dsn, $DB_USER, $DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    };
    $app['prefix'] = $DB_PREFIX;
}
$app['template'] = 'bootstrap';
$app['mustache'] = function($app){
    $path = realpath(sprintf('%s/templates/%s',__DIR__,$app['template']));
    $loader = new Mustache_Loader_FilesystemLoader($path);
    $options  = array(
        'loader'          =>$loader,
        'partials_loader' =>$loader,
    );
    $mustache = new Mustache_Engine($options);
    return $mustache;
};

