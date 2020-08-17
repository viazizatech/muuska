<?php
namespace muuska\security;

interface AuthentificationInfo
{
    /**
     * @return int
     */
    public function getId();
    
    /**
     * @return string
     */
    public function getName();
}