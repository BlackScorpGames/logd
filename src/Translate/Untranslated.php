<?php

namespace blackscorp\logd\Translate;

class Untranslated implements UntranslatedEntity {

    private $intext     =   '';
    
    private $language   =   '';
    
    private $namespace  =   '';
    
    public function getIntext() : string {
        return $this->intext;
    }

    public function getLanguage() : string {
        return $this->language;
    }

    public function getNamespace() : string {
        return $this->namespace;
    }

    public function setIntext(string $intext): void {
        $this->intext = $intext;
    }

    public function setLanguage(string $language): void {
        $this->language = $language;
    }

    public function setNamespace(string $namespace): void {
        $this->namespace = $namespace;
    }

    public function isUntranslated() : bool
    {
        $response = false;
        if ($this->intext)
            $response = true;
        return $response;
    }

}
