<?php

namespace blackscorp\logd\Translate;

interface UntranslatedEntity {
    
    public function getIntext() : string;

    public function getLanguage() : string;

    public function getNamespace() : string;

    public function setIntext(string $intext): void;

    public function setLanguage(string $language): void;

    public function setNamespace(string $namespace): void;
}
