<?php
namespace muuska\instantiator;

class Caches
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Caches
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @return \muuska\cache\FileCacheManager
	 */
	public function createFileCacheManager() {
	    return new \muuska\cache\FileCacheManager();
	}
}
