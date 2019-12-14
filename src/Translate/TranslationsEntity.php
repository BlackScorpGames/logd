<?php
namespace blackscorp\logd\Translate;

interface TranslationsEntity {
    
    public function getId() : int;
    
    public function getLanguage() : string;
    
    public function getNamespace() : string;
    
    public function getIntext() : string;
    
    public function getOuttext() : string;
    
    public function getAuthor() : string;
    
    public function getVersion() : string;
    
    public function setId(int $id): void;
    
    public function setLanguage(string $language): void;
    
    public function setNamespace(string $namespace): void;
    
    public function setIntext(string $intext): void;
    
    public function setOuttext(string $outtext): void;
    
    public function setAuthor(string $author): void;
    
    public function setVersion(string $version): void;
    
    public function isTranslations() : bool;
    
}
