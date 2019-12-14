<?php

use blackscorp\logd\Translate\{Untranslated, UntranslatedEntity, UntranslatedRepository, TranslationsRepository, Translations};

require_once __DIR__.'/vendor/autoload.php';
require_once 'dbconnect.php';

$dsn  = 'mysql:dbname='.$DB_NAME.';host=.'.$DB_HOST.';charset=utf8';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new \PDO($dsn, $DB_USER, $DB_PASS, $options);
$untranslatedRepository = new UntranslatedRepository($pdo);

$translationsRepository = new TranslationsRepository($pdo);    
$allUntranslated        = $untranslatedRepository->findAll();

foreach ($allUntranslated as $untranslated) {
    $translation = $translationsRepository->findByIntext($untranslated->getIntext());   
    if (($translation->isTranslations()) && ($translation->getLanguage() == $untranslated->getLanguage()) && ($translation->getNamespace() == $untranslated->getNamespace())) {
        $untranslatedRepository->delete($untranslated);
        array_shift($allUntranslated);
    }
        
}

if (filter_input(INPUT_POST, 'speichern', FILTER_SANITIZE_STRING) ==='translate') {
    $translations = new Translations();
    $translations->setLanguage(filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING));
    $translations->setNamespace(filter_input(INPUT_POST, 'namespace', FILTER_SANITIZE_STRING));
    $translations->setIntext(filter_input(INPUT_POST, 'intext'));
    $translations->setOuttext(filter_input(INPUT_POST, 'outtext'));
    $translations->setAuthor('BibaltiK');
    $translations->setVersion('1.1.2 Dragonprime Edition');
    $translationsRepository->insert($translations);    
    header('Location: http://'.filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING) . filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING));
    exit;
}
    
    
$untranslated       = $untranslatedRepository->getFirstEntry();

?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            tr:nth-child(even) {
              background-color: #ffe37f;
            }

            tr:nth-child(odd) {
              background-color: #19dc3c;
            }
        </style>
    </head>
    <body>      
        <a href="superuser.php?c=19-200820">zurück zur Grotte</a>
        <form action="untranslated_data.php" method="post">  
            <a>
                <b>Language:</b> <?=$untranslated->getLanguage();?> - 
            </a>
            <input type="hidden" id="language" name="language" value="<?=$untranslated->getLanguage();?>">
            <a>
                <b>Namespace:</b> <?=$untranslated->getNamespace();?> - 
            </a>
            <input type="hidden" id="namespace" name="namespace" value="<?=$untranslated->getNamespace();?>"><br>
            <a>
                <b>intext:</b> <?=htmlspecialchars($untranslated->getIntext());?>
            </a>
            <input type="hidden" id="intext" name="intext" value="<?=htmlspecialchars($untranslated->getIntext());?>"><br>
            <a>
                <b>outtext:</b>
            </a>
            <input type="text" id="outtext" name="outtext" size="255" value="<?=htmlspecialchars($untranslated->getIntext());?>"><br>
            <button type="submit" name="speichern" value="translate">Übersetzen</button>
        </form>
        <table>
            <tr>        
                <th>Language</th>
                <th>Namespace</th>
                <th>intext</th>        
            </tr>
<?php
        foreach ($allUntranslated as $untranslated): ?>
            <tr>        
                <td><?= $untranslated->getLanguage(); ?></td>
                <td><?= $untranslated->getNamespace(); ?></td>
                <td><?= htmlspecialchars($untranslated->getIntext()); ?></td>
            </tr>
<?php   endforeach; ?>
        </table>         
    </body>
</html>