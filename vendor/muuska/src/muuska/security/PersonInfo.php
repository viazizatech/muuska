<?php
namespace muuska\security;

interface PersonInfo
{
    /**
     * @return int
     */
    public function getId();
    
    /**
     * @return string
     */
    public function getFirstName();
    
    /**
     * @return string
     */
    public function getLastName();
}