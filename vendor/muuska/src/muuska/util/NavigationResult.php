<?php
namespace muuska\util;

interface NavigationResult
{
	/**
     * @return bool
     */
    public function hasContent();
	
	/**
     * @return bool
     */
    public function hasRedirection();
    
    /**
     * @return bool
     */
    public function isOperationExecuted();

    /**
     * @return bool
     */
    public function isSuccessfullyExecuted();

    /**
     * @return \muuska\http\redirection\Redirection
     */
    public function getRedirection();

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getContent();

    /**
     * @return string[][]
     */
    public function getAllAlerts();
    
    /**
     * @param string $alertType
     * @return string[]
     */
    public function getAlerts($alertType);
    
    /**
     * @return bool
     */
    public function hasErrors();
}
