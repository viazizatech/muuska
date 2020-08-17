<?php
namespace muuska\util\event;

class SubAppEvent extends EventObject
{
    public function __construct($source, $subAppName, $params = array()){
        parent::__construct($source, $params);
    }
    
    public function getFinalEventCode() {
        return 'app_initialization';
    }
}
