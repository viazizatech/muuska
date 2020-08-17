<?php
namespace muuska\http\redirection;

use muuska\util\App;
use muuska\http\constants\RedirectionType;
use muuska\constants\Names;

class DynamicRedirection extends AbstractRedirection
{
    /**
     * @var string
     */
    protected $controllerName;
    
    /**
     * @var string
     */
    protected $action;
    
    /**
     * @var string
     */
    protected $projectType;
    
    /**
     * @var string
     */
    protected $projectName;
    
    /**
     * @var array
     */
    protected $params;
    
    /**
     * @var string
     */
    protected $successCode;
    
    /**
     * @var string
     */
    protected $errorCode;
    
    /**
     * @var Redirection
     */
    protected $backRedirection;
    
    /**
     * @param string $type
     * @param string $controllerName
     * @param string $action
     * @param array $params
     * @param string $successCode
     * @param string $errorCode
     * @param Redirection $backRedirection
     * @param int $statusCode
     */
    public function __construct($type, $controllerName = null, $action = null, $params = array(), $successCode = null, $errorCode = null, Redirection $backRedirection = null, $statusCode = null){
        parent::__construct($type, $statusCode);
        if(empty($controllerName)){
            $controllerName = $this->getControllerNameFromType($type);
        }
        $this->controllerName = $controllerName;
        $this->action = $action;
        $this->params = $params;
        $this->successCode = $successCode;
        $this->errorCode = $errorCode;
        $this->backRedirection = $backRedirection;
    }
    
    /**
     * @param string $type
     * @return string
     */
    public function getControllerNameFromType($type) {
        $result = '';
        if($type === RedirectionType::LOGIN){
            $result = Names::LOGIN_CONTROLLER;
        }elseif($type === RedirectionType::HOME){
            $result = Names::HOME_CONTROLLER;
        }
        return $result;
    }
    
    public function getFinalUrl(RedirectionInput $input) {
        $finalUrl = '';
        $params = $this->params;
        if($input->isAlertInfoRecordingEnabled() && $input->hasVisitorInfoRecorder()){
            if(!empty($this->successCode)){
                $input->getVisitorInfoRecorder()->setValue($input->getAlertRecorderKey().'success', $this->successCode);
            }
            if(!empty($this->errorCode)){
                $input->getVisitorInfoRecorder()->setValue($input->getAlertRecorderKey().'error', $this->errorCode);
            }
        }else{
            if(!empty($this->successCode)){
                $params['success'] = $this->successCode;
            }
            if(!empty($this->errorCode)){
                $params['error'] = $this->errorCode;
            }
        }
        if($this->hasBackRedirection()){
            if($input->isBackInfoRecordingEnabled() && $input->hasVisitorInfoRecorder()){
                $input->getVisitorInfoRecorder()->setValue($input->getBackRecorderKey(), $this->backRedirection->getFinalUrl($input));
            }else{
                $params['back'] = $this->backRedirection->getFinalUrl($input);
            }
        }
        if(empty($this->controllerName)){
            $finalUrl = $input->getControllerUrlCreator()->createUrl($this->action, $params);
        }elseif(empty($this->projectType)){
            $finalUrl = $input->getControllerUrlCreator()->createControllerUrl($this->controllerName, $this->action, $params, false);
        }else{
            $finalUrl = App::getApp()->createUrl($input->getControllerUrlCreator()->createUrlInput($this->projectType, $this->projectType, $this->controllerName, $this->action, $params));
        }
        return $finalUrl;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     */
    public function addParam($name, $value){
        $this->params[$name] = $value;
    }
    
    /**
     * @return bool
     */
    public function hasBackRedirection(){
        return ($this->backRedirection !== null);
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getSuccessCode()
    {
        return $this->successCode;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return Redirection
     */
    public function getBackRedirection()
    {
        return $this->backRedirection;
    }
    
    /**
     * @param string $projectType
     */
    public function setProjectType($projectType)
    {
        $this->projectType = $projectType;
    }

    /**
     * @param string $projectName
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
    }
    /**
     * @param string $controllerName
     */
    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @param string $successCode
     */
    public function setSuccessCode($successCode)
    {
        $this->successCode = $successCode;
    }

    /**
     * @param string $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @param \muuska\http\redirection\Redirection $backRedirection
     */
    public function setBackRedirection(\muuska\http\redirection\Redirection $backRedirection)
    {
        $this->backRedirection = $backRedirection;
    }
}
