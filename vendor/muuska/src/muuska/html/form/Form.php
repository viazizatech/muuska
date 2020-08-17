<?php
namespace muuska\html\form;

use muuska\html\ChildrenContainer;

class Form extends ChildrenContainer{
    /**
     * @var string
     */
    protected $componentName = 'form';
    
	/**
	 * @var string
	 */
	protected $action;
	
	/**
	 * @var string
	 */
	protected $method = 'post';
	
	/**
	 * @var string
	 */
	protected $enctype = 'multipart/form-data';
	
	/**
	 * @var string
	 */
	protected $submittedText = 'submitted';
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $submit;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $cancel;
	
	/**
	 * @var string
	 */
	protected $errorText;
	
	/**
	 * @param string $action
	 */
	public function __construct($action = '') {
	    $this->setAction($action);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if(!empty($this->action)){
	        $attributes['action'] = $this->action;
	    }
	    $attributes['method'] = $this->method;
	    $attributes['enctype'] = $this->enctype;
	    return $attributes;
	}
	
	/**
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	public function drawErrorText($prefix = '', $suffix = '') {
	    return $this->drawString($this->errorText, $prefix, $suffix);
	}
	
	/**
	 * @return bool
	 */
	public function hasFooter(){
	    return ($this->hasSubmit() || $this->hasCancel());
	}
	
	/**
	 * @return boolean
	 */
	public function hasCancel(){
		return ($this->cancel !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasSubmit(){
	    return ($this->submit !== null);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderSubmit(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->submit, $globalConfig, $callerConfig, 'submit', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderCancel(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->cancel, $globalConfig, $callerConfig, 'cancel', $prefix, $suffix, $currentCallerConfig);
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
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getEnctype()
    {
        return $this->enctype;
    }

    /**
     * @return string
     */
    public function getSubmittedText()
    {
        return $this->submittedText;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getSubmit()
    {
        return $this->submit;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getCancel()
    {
        return $this->cancel;
    }

    /**
     * @return string
     */
    public function getErrorText()
    {
        return $this->errorText;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param string $enctype
     */
    public function setEnctype($enctype)
    {
        $this->enctype = $enctype;
    }

    /**
     * @param string $submittedText
     */
    public function setSubmittedText($submittedText)
    {
        $this->submittedText = $submittedText;
    }

    /**
     * @param \muuska\html\HtmlContent $submit
     */
    public function setSubmit(?\muuska\html\HtmlContent $submit)
    {
        $this->submit = $submit;
    }

    /**
     * @param \muuska\html\HtmlContent $cancel
     */
    public function setCancel(?\muuska\html\HtmlContent $cancel)
    {
        $this->cancel = $cancel;
    }

    /**
     * @param string $errorText
     */
    public function setErrorText($errorText)
    {
        $this->errorText = $errorText;
    }
}