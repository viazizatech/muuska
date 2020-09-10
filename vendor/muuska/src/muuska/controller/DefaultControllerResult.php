<?php
namespace muuska\controller;
use muuska\html\constants\AlertType;
use muuska\util\AbstractExtraDataProvider;

class DefaultControllerResult extends AbstractExtraDataProvider implements ControllerResult{
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $content;
    
    /**
     * @var array
     */
    protected $allAlerts;
	
	/**
	 * @var \muuska\asset\AssetSetter
	 */
	protected $assetSetter;
	
	/**
	 * @var \muuska\http\redirection\Redirection
	 */
	protected $redirection;
	
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @param \muuska\asset\AssetSetter $assetSetter
	 */
	public function __construct(\muuska\asset\AssetSetter $assetSetter){
	    $this->assetSetter = $assetSetter;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\ControllerResult::getTitle()
	 */
	public function getTitle(){
	    return $this->title;
	}
	
	/**
	 * @param string $title
	 */
	public function setTitle($title){
	    $this->title = $title;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\ControllerResult::hasRedirection()
	 */
	public function hasRedirection(){
	    return ($this->redirection !== null);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\ControllerResult::getRedirection()
	 */
	public function getRedirection(){
	    return $this->redirection;
	}
	
	/**
	 * @param \muuska\http\redirection\Redirection $redirection
	 */
	public function setRedirection(\muuska\http\redirection\Redirection $redirection){
	    $this->redirection = $redirection;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\ControllerResult::hasContent()
	 */
	public function hasContent(){
	    return ($this->content !== null);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\ControllerResult::getContent()
	 */
	public function getContent(){
	    return $this->content;
	}
	
	/**
	 * @param \muuska\html\HtmlContent $content
	 */
	public function setContent(\muuska\html\HtmlContent $content){
	    $this->content = $content;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\ControllerResult::getAllAlerts()
	 */
	public function getAllAlerts(){
	    return $this->allAlerts;
	}
	
	/**
	 * @param array $allAlerts
	 */
	public function setAllAlerts($allAlerts){
	    $this->allAlerts = array();
	    $this->addAllAlerts($allAlerts);
	}
	
	/**
	 * @param array $allAlerts
	 */
	public function addAllAlerts($allAlerts){
	    if (is_array($allAlerts)) {
	        foreach ($allAlerts as $alertType => $messages) {
	            $this->addAlerts($alertType, $messages);
	        }
	    }
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\ControllerResult::getAlerts()
	 */
	public function getAlerts($alertType){
	    return isset($this->allAlerts[$alertType]) ? $this->allAlerts[$alertType] : array();
	}
	
	/**
	 * @param string $alertType
	 * @param array $alerts
	 */
	public function setAlerts($alertType, $alerts){
	    $this->allAlerts[$alertType] = $alerts;
	}
	
	/**
	 * @param string $alertType
	 * @param string $message
	 */
	public function addAlert($alertType, $message){
	    $this->allAlerts[$alertType][] = $message;
	}
	
	/**
	 * @param string $alertType
	 * @param array $messages
	 */
	public function addAlerts($alertType, $messages){
	    if (is_array($messages)) {
	        foreach ($messages as $value) {
	            $this->addAlert($alertType, $value);
	        }
	    }
	}
	
	/**
	 * @param string $error
	 */
	public function addError($error){
	    $this->addAlert(AlertType::DANGER, $error);
	}
	
	/**
	 * @param array $errors
	 */
	public function addErrors($errors){
	    $this->addAlerts(AlertType::DANGER, $errors);
	}
	
	/**
	 * @param string $message
	 */
	public function addSuccess($message){
	    $this->addAlert(AlertType::SUCCESS, $message);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\ControllerResult::getAssetSetter()
	 */
	public function getAssetSetter(){
	    return $this->assetSetter;
	}
	
    /**
     * {@inheritDoc}
     * @see \muuska\controller\ControllerResult::hasErrors()
     */
    public function hasErrors()
    {
        return !empty($this->getAlerts(AlertType::DANGER));
    }
}