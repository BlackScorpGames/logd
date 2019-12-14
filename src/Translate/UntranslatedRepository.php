<?php

namespace blackscorp\logd\Translate;

use blackscorp\logd\Translate\{Untranslated, UntranslatedEntity};

use PDO;

class UntranslatedRepository 
{
    private $pdo    =   null;
    
    public function __construct(\PDO $pdo) 
    {
        $this->pdo = $pdo;
    }
    
    public function setAll(array $untranslated)
    {
        $response = new Untranslated();
        if ($untranslated) {
            $response->setLanguage($untranslated['language']);
            $response->setNamespace($untranslated['namespace']);
            $response->setIntext($untranslated['intext']);
        }
        return $response;
    }
    
    public function findAll() : array 
    {
        $sql = "SELECT `intext`, `language`, `namespace` FROM `untranslated`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $response = [];        
        foreach ($result as $untranslated)
            $response[] = $this->setAll($untranslated);
        return $response;
    }
    
    public function getFirstEntry() : UntranslatedEntity
    {
        $sql = "SELECT `intext`, `language`, `namespace` FROM `untranslated`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $response = new Untranslated();
        if ($result)
            $response = $this->setAll($result);
        return $response;
    }
    
    public function delete(UntranslatedEntity $untranslated) {
        $sql = "DELETE FROM "
                    . "`untranslated` "
                . "WHERE "
                    . "`intext`=:intext "
                . "AND "
                    . "`language`=:language "
                . "AND "
                    . "`namespace`=:namespace";
        $stmt = $this->pdo->prepare($sql);
        $intext = $untranslated->getIntext();
        $language = $untranslated->getLanguage();
        $namespace = $untranslated->getNamespace();
        $stmt->bindParam(':intext', $intext, PDO::PARAM_STR);
        $stmt->bindParam(':language', $language, PDO::PARAM_STR);
        $stmt->bindParam(':namespace', $namespace, PDO::PARAM_STR);
        $stmt->execute();
    }
}
