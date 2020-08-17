<?php
namespace muuska\url;

use muuska\constants\ActionCode;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class DefaultControllerUrl implements ControllerUrlCreator
{
    /**
     * @var \muuska\controller\ControllerInput
     */
    protected $controllerInput;
    
    /**
     * @var array
     */
    protected $initialParams;
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param array $initialParams
     */
    public function __construct(\muuska\controller\ControllerInput $controllerInput, $initialParams = null){
        $this->controllerInput = $controllerInput;
        $this->initialParams = $initialParams;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\ControllerUrlCreator::createDefaultUrl()
     */
    public function createDefaultUrl($params = array(), $anchor = '', $mode = null){
        return $this->createUrl(ActionCode::DEFAULT_PROCESS, $params, $anchor, $mode);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\ControllerUrlCreator::createCurrentActionUrl()
     */
    public function createCurrentActionUrl($params = array(), $anchor = '', $mode = null){
        return $this->createUrl($this->controllerInput->getAction(), $params, $anchor, $mode);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\ControllerUrlCreator::createUrl()
     */
    public function createUrl($action, $params = array(), $anchor = '', $mode = null){
        return $this->createControllerUrl($this->controllerInput->getName(), $action, $params, true, $anchor, $mode);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\ControllerUrlCreator::createControllerUrl()
     */
    public function createControllerUrl($controllerName, $action, $params = array(), $useInitialParams = false, $anchor = '', $mode = null)
    {
        if($useInitialParams && !empty($this->initialParams)){
            if(empty($params)){
                $params = $this->initialParams;
            }else {
                $params = array_merge($this->initialParams, $params);
            }
        }
        return App::getApp()->createUrl($this->createUrlInput($this->controllerInput->getProject()->getType(), $this->controllerInput->getProject()->getName(), $controllerName, $action, $params, $anchor, $mode));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\ControllerUrlCreator::createModuleUrl()
     */
    public function createModuleUrl($moduleName, $controllerName, $action = null, $params = array(), $anchor = '', $mode = null)
    {
        return App::getApp()->createUrl($this->createUrlInput(ProjectType::MODULE, $moduleName, $controllerName, $action, $params, $anchor, $mode));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\ControllerUrlCreator::createUrlInput()
     */
    public function createUrlInput($projectType, $projectName, $controllerName, $action = null, $params = array(), $anchor = '', $mode = null)
    {
        return App::urls()->createUrlCreationInput($this->controllerInput->getSubAppName(), $this->controllerInput->getLang(), $controllerName, $action, $params, $projectType, $projectName, $anchor, $this->controllerInput->getVariationTriggers(), $mode);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\ControllerUrlCreator::createFullUrl()
     */
    public function createFullUrl(UrlCreationInput $input)
    {
        return App::getApp()->createUrl($input);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\ControllerUrlCreator::createModelUrlCreator()
     */
    public function createModelUrlCreator(\muuska\model\ModelDefinition $modelDefinition, $action){
        return App::urls()->createModelUrl($this->controllerInput, $modelDefinition, $action, $this->initialParams);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\ControllerUrlCreator::createArrayUrlCreator()
     */
    public function createArrayUrlCreator($defaultUrl = null){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\pagination\ListLimiterUrl::createListLimiterUrl()
     */
    public function createListLimiterUrl($value)
    {
        return $this->createDefaultUrl(array('limit' => $value));
    }

    /**
     * {@inheritDoc}
     * @see \muuska\url\pagination\PaginationUrl::createPageUrl()
     */
    public function createPageUrl($page)
    {
        return $this->createDefaultUrl(array('p' => $page));
    }

    /**
     * {@inheritDoc}
     * @see \muuska\url\pagination\ListLimiterUrl::getEditableListLimiterUrlPattern()
     */
    public function getEditableListLimiterUrlPattern()
    {
        
    }
}