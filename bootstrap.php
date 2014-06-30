<?php
require_once __DIR__.'/vendor/autoload.php';
require 'dbconnect.php';

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8',$DB_HOST,$DB_NAME);
$pdo = new PDO($dsn,$DB_USER,$DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);