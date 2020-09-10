<?php
namespace muuska\util\event;

class EventObject
{
    /**
     * @var object
     */
    protected $source;
    
    /**
     * @var array
     */
    protected $params;
    
    /**
     * @var array
     */
    protected $executionParams;
    
    /**
     * @var bool
     */
    protected $defaultPrevented;
    
    /**
     * @var bool
     */
    protected $propagationStopped;
    
	/**
	 * @param object $source
	 * @param array $params
	 */
	public function __construct($source, $params = array()){
	    $this->source = $source;
	    $this->params = $params;
	}
	
	public function stopPropagation(){
	    $this->propagationStopped = true;
	}
	
	public function preventDefault(){
	    $this->defaultPrevented = true;
	}
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	public function hasParam($name){
	    return isset($this->params[$name]);
	}
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getParam($name){
	    return $this->hasParam($name) ? $this->params[$name] : null;
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasExecutionParam($name){
	    return isset($this->executionParams[$name]);
	}
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getExecutionParam($name){
	    return $this->hasExecutionParam($name) ? $this->executionParams[$name] : null;
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function addExecutionParam($name, $value){
	    $this->setExecutionParam($name, $value);
	}
	
	/**
	 * @param array $params
	 */
	public function addExecutionParams($params){
	    if(is_array($params)){
	        foreach ($params as $key => $value) {
	            $this->addExecutionParam($key, $value);
	        }
	    }
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setExecutionParam($name, $value){
	    $this->executionParams[$name] = $value;
	}
	
    /**
     * @return object
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return boolean
     */
    public function isDefaultPrevented()
    {
        return $this->defaultPrevented;
    }

    /**
     * @return boolean
     */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }
    
    /**
     * @return array
     */
    public function getExecutionParams()
    {
        return $this->executionParams;
    }

    /**
     * @param array $executionParams
     */
    public function setExecutionParams($executionParams)
    {
        $this->executionParams = array();
        $this->addExecutionParams($executionParams);
    }
}
