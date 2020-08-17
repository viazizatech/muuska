<?php
namespace muuska\translation\config;

interface TranslatorConfig
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getType();
}