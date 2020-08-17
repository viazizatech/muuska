<?php
namespace muuska\security;

interface GroupInfo
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