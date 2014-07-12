<?php


namespace Logd\Core\Service;


/**
 * Interface PasswordHasher
 * @package Logd\Core\Service
 */
interface PasswordHasher {
    /**
     * @param string $rawPassword
     * @return string
     */
    public function hash($rawPassword);

    /**
     * @param string $hash
     * @param string $string
     * @return bool
     */
    public function verify($hash,$string);
} 