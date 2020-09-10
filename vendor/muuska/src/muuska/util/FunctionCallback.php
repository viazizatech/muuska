<?php
namespace muuska\util;
class FunctionCallback{
    
	/**
	 * @var callable
	 */
	protected $callback;
	
	/**
	 * @var array
	 */
	protected $initialParams;
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 */
	public function __construct($callback, $initialParams = null) {
		$this->setCallback($callback);
		$this->setInitialParams($initialParams);
	}
	
	/**
	 * @param callable $callback
	 */
	protected function setCallback($callback) {
	    if(is_callable($callback)){
			$this->callback = $callback;
		}
	}
	
	/**
	 * @param array $initialParams
	 */
	protected function setInitialParams($initialParams)
    {
        $this->initialParams = $initialParams;
    }
    
    /**
     * @param array $params
     * @return mixed
     */
    public function call($params = array()) {
        $result = null;
        if($this->callback !== null){
            if(empty($this->initialParams)){
                $result = call_user_func_array($this->callback, $params);
            }else{
                $finalParams = array();
                if(is_array($this->initialParams)){
                    $finalParams = array('initialParams' => $this->initialParams);
                }
                if(is_array($params)){
                    $finalParams = array_merge($finalParams, $params);
                }
                $result = call_user_func_array($this->callback, $finalParams);
            }
        }
        return $result;
    }
}