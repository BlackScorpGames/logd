<?php

namespace blackscorp\logd\Translate;

use blackscorp\logd\TranslateEntity;

class Translations implements TranslationsEntity {
    
    private $id         =   0;
    
    private $language   = 'de';
    
    private $namespace  =   '';
    
    private $intext     =   '';
    
    private $outtext    =   '';
    
    private $author     =   '';
    
    private $version    =   '';
    
    public function getId() : int 
    {
        return $this->id;
    }

    public function getLanguage() : string 
    {
        return $this->language;
    }

    public function getNamespace() : string 
    {
        return $this->namespace;
    }

    public function getIntext() : string 
    {
        return $this->intext;
    }

    public function getOuttext() : string 
    {
        return $this->outtext;
    }

    public function getAuthor() : string 
    {
        return $this->author;
    }

    public function getVersion() : string 
    {
        return $this->version;
    }

    public function setId(int $id): void 
    {
        $this->id = $id;
    }

    public function setLanguage(string $language): void 
    {
        $this->language = $language;
    }

    public function setNamespace(string $namespace): void 
    {
        $this->namespace = $namespace;
    }

    public function setIntext(string $intext): void 
    {
        $this->intext = $intext;
    }

    public function setOuttext(string $outtext): void 
    {
        $this->outtext = $outtext;
    }

    public function setAuthor(string $author): void 
    {
        $this->author = $author;
    }

    public function setVersion(string $version): void 
    {
        $this->version = $version;
    }
    
    public function isTranslations() : bool
    {
        $response = false;
        if ($this->id > 0)
            $response = true;
        return $response;
    }


}
