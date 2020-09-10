<?php
namespace muuska\security;

interface PersonInfoResolver
{
    /**
     * @param AuthentificationInfo $authentificationInfo
     * @return PersonInfo
     */
    public function getPersonInfo(AuthentificationInfo $authentificationInfo);
}