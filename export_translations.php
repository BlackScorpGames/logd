<?php

use blackscorp\logd\Translate\{Translations, TranslationsEntity, TranslationsRepository};


require_once __DIR__.'/vendor/autoload.php';
require_once 'dbconnect.php';


$dsn  = 'mysql:dbname='.$DB_NAME.';host=.'.$DB_HOST.';charset=utf8';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new \PDO($dsn, $DB_USER, $DB_PASS, $options);
$translationsRepository = new TranslationsRepository($pdo);

$handle = fopen('translations_german.sql', "w");
fwrite($handle, "START TRANSACTION;\n");
fwrite($handle, "DROP TABLE IF EXISTS `translations`;\n");
fwrite($handle, "CREATE TABLE `translations` (
  `tid` int(11) NOT NULL,
  `language` varchar(10) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `intext` blob NOT NULL,
  `outtext` blob NOT NULL,
  `author` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
fwrite($handle, "ALTER TABLE `translations`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `language` (`language`,`uri`),
  ADD KEY `uri` (`uri`);
\n");
fwrite($handle, "ALTER TABLE `translations`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT;\n");



$allTranslations = $translationsRepository->findAll();

foreach ($allTranslations as $translations) {
    
    $data = "INSERT INTO `translations` (`language`, `uri`, `intext`, `outtext`, `author`, `version`) "
            . "VALUES ( "
            . "'".$translations->getLanguage()."', '".$translations->getNamespace()."', 0x".bin2hex($translations->getIntext()).", 0x".bin2hex($translations->getOuttext()).", '".$translations->getAuthor()."', '".$translations->getVersion()."');\n";
    fwrite($handle, $data);
}

fwrite($handle, "COMMIT;\n");
?>
<a href="translated_data.php">Daten überschrieben -  zurück</a>

