<?php
namespace blackscorp\logd\Translate;

use blackscorp\logd\{Translate, TranslateEntity};

use PDO;

class TranslationsRepository 
{    
    private $pdo    =   null;
    
    public function __construct(\PDO $pdo) 
    {
        $this->pdo = $pdo;
    }
    
    public function setAll(array $translations)
    {
        $response = new Translations();
        if ($translations) {
            $response->setId($translations['tid']);
            $response->setLanguage($translations['language']);
            $response->setNamespace($translations['uri']);
            $response->setIntext($translations['intext']);
            $response->setOuttext($translations['outtext']);
            $response->setAuthor($translations['author']);
            $response->setVersion($translations['version']);
        }
        return $response;
    }

    public function findByID(int $id) : TranslationsEntity 
    {
        $response = new Translations();
        if ($id > 0) {
            $sql = "SELECT `tid`, `language`, `uri`, `intext`, `outtext`, `author`, `version` FROM `translations` WHERE `tid`=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
        }        
        $response = $this->setAll($result);        
        return $response;        
    }
    
    public function findByIntext(string $intext)
    {
        $sql = "SELECT `tid`, `language`, `uri`, `intext`, `outtext`, `author`, `version` FROM `translations` WHERE `intext`=:intext";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':intext', $intext, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        $response = new Translations();
        if ($result)
            $response = $this->setAll($result);
        return $response;
    }


    public function findAll() : array 
    {
        $sql = "SELECT `tid`, `language`, `uri`, `intext`, `outtext`, `author`, `version` FROM `translations`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $response = [];        
        foreach ($result as $translations)
            $response[] = $this->setAll ($translations);
        return $response;
    }
    
    public function insert(TranslationsEntity $translations) : void
    {
        $sql = "INSERT INTO "
                    . "`translations` "
                . "(`language`, `uri`, `intext`, `outtext`, `author`, `version`) "
                . "VALUES "
                    . "(:language, :namespace, :intext, :outtext, :author, :version)";
        $stmt = $this->pdo->prepare($sql);
        $language=  $translations->getLanguage();
        $namespace= $translations->getNamespace();
        $intext=    $translations->getIntext();
        $outtext=   $translations->getOuttext();
        $author =   $translations->getAuthor();
        $version=   $translations->getVersion();
        $stmt->bindParam(':language', $language);
        $stmt->bindParam(':namespace', $namespace);
        $stmt->bindParam(':intext', $intext);
        $stmt->bindParam(':outtext', $outtext);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':version', $version);
        $stmt->execute();
    }


    public function update(TranslationsEntity $translations) : void
    {
        $sql = "UPDATE "
                    . "`translations` "
                . "SET "
                    . "`language`=:language, "
                    . "`uri`=:namespace, "
                    . "`intext`=:intext, "
                    . "`outtext`=:outtext, "
                    . "`author`=:author, "
                    . "`version`=:version "
                . "WHERE "
                    . "`tid`=:id";
        $stmt = $this->pdo->prepare($sql);
        $id=$translations->getId();
        $language=  $translations->getLanguage();
        $namespace= $translations->getNamespace();
        $intext=    $translations->getIntext();
        $outtext=   $translations->getOuttext();
        $author =   $translations->getAuthor();
        $version=   $translations->getVersion();
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':language', $language);
        $stmt->bindParam(':namespace', $namespace);
        $stmt->bindParam(':intext', $intext);
        $stmt->bindParam(':outtext', $outtext);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':version', $version);
        $stmt->execute();
    }
    
    public function delete(int $id) : void
    {
        $sql = "DELETE FROM "
                    . "`translations` "
                . "WHERE "
                    . "`tid`=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
