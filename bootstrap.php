<?php
require_once __DIR__ . '/vendor/autoload.php';
use Logd\Core\Env;
use  Logd\Core\App\NavigationCollection;
use Logd\Core\App\Navigation;
if (!Env::isTest()) {
    if(file_exists('dbconnect.php'))
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



$navigationCreate = new Navigation('Create a character');
$navigationCreate->link = 'create.php';
$navigationForgotPassword = new Navigation('Forgotten Password');
$navigationForgotPassword->link = 'create.php?op=forgot';
$navigationListWarriors = new Navigation('List Warriors');
$navigationListWarriors->link = 'list.php';
$navigationNews = new Navigation('Daily News');
$navigationNews->link = 'news.php';
$navigationAbout = new Navigation('About LoGD');
$navigationAbout->link = 'about.php';
$navigationSetupInfo = new Navigation('Game Setup Info');
$navigationSetupInfo->link = 'about.php?op=setup';
$navigationLoGDNet = new Navigation('LoGD Net');
$navigationLoGDNet->link = 'logdnet.php?op=list';

$navigationCollection = new NavigationCollection();
$navigationCollection->add(new Navigation('New to Logd?'));
$navigationCollection->add($navigationCreate);
$navigationCollection->add(new Navigation('Game Functions'));
$navigationCollection->add($navigationForgotPassword);
$navigationCollection->add($navigationListWarriors);
$navigationCollection->add($navigationNews);
$navigationCollection->add(new Navigation('Other Info'));
$navigationCollection->add($navigationAbout);
$navigationCollection->add($navigationSetupInfo);
$navigationCollection->add($navigationLoGDNet);

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
$app['navigation'] = function() use($navigationCollection){
    return $navigationCollection;
};
