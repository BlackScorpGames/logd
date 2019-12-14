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



if (filter_input(INPUT_POST, 'speichern', FILTER_SANITIZE_STRING) ==='edit') {
    $translations = new Translations();
    $translations->setId(filter_input((int)INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
    $translations->setLanguage(filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING));
    $translations->setNamespace(filter_input(INPUT_POST, 'namespace', FILTER_SANITIZE_STRING));
    $translations->setIntext(filter_input(INPUT_POST, 'intext'));
    $translations->setOuttext(filter_input(INPUT_POST, 'outtext'));
    $translations->setAuthor(filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING));
    $translations->setVersion(filter_input(INPUT_POST, 'version', FILTER_SANITIZE_STRING));
    $translationsRepository->update($translations);
}
if (filter_input(INPUT_POST, 'speichern', FILTER_SANITIZE_STRING) ==='yes') {
    $translationsRepository->delete(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
}

$allTranslations = $translationsRepository->findAll();
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
        <a href="superuser.php?c=19-200820">zurück zur Grotte</a> - | - <a href="export_translations.php">Export/overwrite translations_german.sql</a>
<?php
        if (filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING)==='edit') :
            
            $translations = $translationsRepository->findByID($_GET['id']);
?>    
        <form action="translated_data.php" method="post">  
            <input type="hidden" id="id" name="id" value="<?=$translations->getID();?>">
            <a><b>Language:</b> <?=$translations->getLanguage();?> - </a>
            <input type="hidden" id="language" name="language" value="<?=$translations->getLanguage();?>">
            <a><b>Namespace:</b> <?=$translations->getNamespace();?> - </a>
            <input type="hidden" id="namespace" name="namespace" value="<?=$translations->getNamespace();?>">
            <a><b>intext:</b> <?=htmlspecialchars($translations->getIntext());?></a>
            <input type="hidden" id="intext" name="intext" value="<?=htmlspecialchars($translations->getIntext());?>">
            <a><b>outtext:</b></a>
            <input type="text" id="outtext" name="outtext" value="<?=htmlspecialchars($translations->getOuttext());?>">
            <input type="hidden" id="author" name="author" value="<?=$translations->getAuthor();?>">
            <input type="hidden" id="version" name="version" value="<?=$translations->getVersion();?>">
            <button type="submit" name="speichern" value="edit">speichern</button>
        </form>
<?php   endif;

        if (filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING)==='del') :    
            $translations = $translationsRepository->findByID(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
?>    
        <form action="translated_data.php" method="post">  
            <input type="hidden" id="id" name="id" value="<?=$translations->getID();?>">
            <a><b>Language:</b> <?=$translations->getLanguage();?> - </a>            
            <a><b>Namespace:</b> <?=$translations->getNamespace();?> - </a>            
            <a><b>intext:</b> <?=htmlspecialchars($translations->getIntext());?></a>            
            <a><b>outtext:</b></a> <?=htmlspecialchars($translations->getOuttext());?>            
            <button type="submit" name="speichern" value="yes">Löschen Bestätigen</button>
        </form>
<?php   endif;  ?>

        <table>
            <tr>        
                <th>id</th>
                <th>Language</th>
                <th>Namespace</th>
                <th>intext</th>
                <th>outtext</th>
                <th>Author</th>
                <th>Version</th>
                <th>Options</th>
            </tr>

<?php foreach ($allTranslations as $translations) : ?>
            <tr>        
                <td><?= $translations->getID(); ?></td>
                <td><?= $translations->getLanguage(); ?></td>
                <td><?= $translations->getNamespace(); ?></td>
                <td><?= htmlspecialchars($translations->getIntext()); ?></td>
                <td><?= htmlspecialchars($translations->getOuttext()); ?></td>
                <td><?= $translations->getAuthor(); ?></td>
                <td><?= $translations->getVersion(); ?></td>
                <td><a href="translated_data.php?action=edit&id=<?=$translations->getID();?>">edit</a>|<a href="translated_data.php?action=del&id=<?=$translations->getID();?>">del</a></td>
            </tr>
<?php endforeach; ?>
        </table>         
    </body>
</html>