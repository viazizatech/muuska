<?php
namespace muuska\controller;

use muuska\util\ExtraDataProvider;

interface ControllerResult extends ExtraDataProvider
{
    /**
     * @return string
     */
    public function getTitle();
    
    /**
     * @return bool
     */
    public function hasRedirection();
    
    /**
     * @return \muuska\http\redirection\Redirection
     */
    public function getRedirection();
    
    /**
     * @return bool
     */
    public function hasContent();
    
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getContent();
    
    /**
     * @return array
     */
    public function getAllAlerts();
    
    /**
     * @param string $alertType
     * @return array
     */
    public function getAlerts($alertType);
    
    /**
     * @return bool
     */
    public function hasErrors();
    
    /**
     * @return \muuska\asset\AssetSetter
     */
    public function getAssetSetter();
}
