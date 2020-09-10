<?php
namespace muuska\url\objects;

use muuska\util\App;

class ModelUrl implements ObjectUrl
{
    /**
     * @var \muuska\controller\ControllerInput
     */
    protected $controllerInput;
    
    /**
     * @var string
     */
    protected $action;
    
    /**
     * @var array
     */
    protected $initialParams;
    
    /**
     * @var \muuska\model\ModelDefinition
     */
    protected $modelDefinition;
    
    /**
     * @var bool
     */
    protected $currentControllerNameEnabled;
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $action
     * @param array $initialParams
     */
    public function __construct(\muuska\controller\ControllerInput $controllerInput, \muuska\model\ModelDefinition $modelDefinition, $action, $initialParams = null){
        $this->controllerInput = $controllerInput;
        $this->modelDefinition = $modelDefinition;
        $this->action = $action;
        $this->initialParams = $initialParams;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\objects\ObjectUrl::createUrl()
     */
    public function createUrl($data, $params = array(), $anchor = '', $mode = null)
    {
        return $this->createActionUrl($data, $this->action, $params, $anchor, $mode);
    }
    
    /**
     * @param mixed $data
     * @param string $action
     * @param array $params
     * @param string $anchor
     * @param int $mode
     * @return string
     */
    public function createActionUrl($data, $action, $params = array(), $anchor = '', $mode = null)
    {
        if(!empty($this->initialParams)){
            if(empty($params)){
                $params = $this->initialParams;
            }else {
                $params = array_merge($this->initialParams, $params);
            }
        }
        return App::getApp()->createUrl($this->modelDefinition->createUrlInput($data, $this->controllerInput, $action, $params, $this->currentControllerNameEnabled, $anchor, $mode));
    }
    /**
     * @return boolean
     */
    public function isCurrentControllerNameEnabled()
    {
        return $this->currentControllerNameEnabled;
    }

    /**
     * @param boolean $currentControllerNameEnabled
     */
    public function setCurrentControllerNameEnabled($currentControllerNameEnabled)
    {
        $this->currentControllerNameEnabled = $currentControllerNameEnabled;
    }

}