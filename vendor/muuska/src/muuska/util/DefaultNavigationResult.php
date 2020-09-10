<?php
namespace muuska\util;

use muuska\html\constants\AlertType;

class DefaultNavigationResult implements NavigationResult
{
    /**
     * @var bool
     */
    protected $operationExecuted;
    
    /**
     * @var bool
     */
    protected $successfullyExecuted;
    
    /**
     * @var \muuska\http\redirection\Redirection
     */
    protected $redirection;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $content;
    
    /**
     * @var array
     */
    protected $allAlerts;
    
    public function __construct($operationExecuted, $successfullyExecuted = false, \muuska\http\redirection\Redirection $redirection = null, \muuska\html\HtmlContent $content = null, $allAlerts = array()) {
        $this->operationExecuted = $operationExecuted;
        $this->successfullyExecuted = $successfullyExecuted;
        $this->redirection = $redirection;
        $this->content = $content;
        $this->allAlerts = $allAlerts;
    }
    
    public function hasContent()
    {
        return ($this->content !== null);
    }
    public function hasRedirection()
    {
        return ($this->redirection !== null);
    }
    
    public function isOperationExecuted()
    {
        return $this->operationExecuted;
    }
    
    public function getRedirection()
    {
        return $this->redirection;
    }
    
    public function isSuccessfullyExecuted()
    {
        return $this->successfullyExecuted;
    }
    public function getContent()
    {
        return $this->content;
    }
    
    public function getAllAlerts()
    {
        $this->allAlerts;
    }
    
    public function getAlerts($alertType)
    {
        return isset($this->allAlerts[$alertType]) ? $this->allAlerts[$alertType] : array();
    }
    public function hasErrors()
    {
        return !empty($this->getAlerts(AlertType::DANGER));
    }
}
