<?php
namespace muuska\util\event;

class AppInitializationEvent extends EventObject
{
    /**
     * @param \muuska\project\Application $source
     * @param array $params
     */
    public function __construct(\muuska\project\Application $source, $params = array()){
        parent::__construct($source, $params);
    }
    
    /**
     * @return string
     */
    public function getFinalEventCode() {
        return 'app_initialization';
    }
}
