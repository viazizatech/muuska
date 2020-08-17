<?php
namespace muuska\url;

use muuska\url\pagination\ListLimiterUrl;
use muuska\url\pagination\PaginationUrl;

interface ControllerUrlCreator extends PaginationUrl, ListLimiterUrl
{
    /**
     * @param array $params
     * @param string $anchor
     * @param int $mode
     * @return string
     */
    public function createDefaultUrl($params = array(), $anchor = '', $mode = null);
    
    /**
     * @param array $params
     * @param string $anchor
     * @param int $mode
     * @return string
     */
    public function createCurrentActionUrl($params = array(), $anchor = '', $mode = null);
    
    /**
     * @param string $action
     * @param array $params
     * @param string $anchor
     * @param int $mode
     * @return string
     */
    public function createUrl($action, $params = array(), $anchor = '', $mode = null);
    
    /**
     * @param string $controllerName
     * @param string $action
     * @param array $params
     * @param bool $useInitialParams
     * @param string $anchor
     * @param int $mode
     * @return string
     */
    public function createControllerUrl($controllerName, $action, $params = array(), $useInitialParams = false, $anchor = '', $mode = null);
    
    /**
     * @param string $moduleName
     * @param string $controllerName
     * @param string $action
     * @param array $params
     * @param string $anchor
     * @param int $mode
     * @return string
     */
    public function createModuleUrl($moduleName, $controllerName, $action = null, $params = array(), $anchor = '', $mode = null);
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $action
     * @return \muuska\url\objects\ModelUrl
     */
    public function createModelUrlCreator(\muuska\model\ModelDefinition $modelDefinition, $action);
    
    /**
     * @param string $defaultUrl
     * @return \muuska\url\objects\ArrayUrl
     */
    public function createArrayUrlCreator($defaultUrl = null);
    
    /**
     * @param UrlCreationInput $input
     * @return string
     */
    public function createFullUrl(UrlCreationInput $input);
    
    /**
     * @param int $projectType
     * @param string $projectName
     * @param string $controllerName
     * @param string $action
     * @param array $params
     * @param string $anchor
     * @param int $mode
     * @return UrlCreationInput
     */
    public function createUrlInput($projectType, $projectName, $controllerName, $action = null, $params = array(), $anchor = '', $mode = null);
}