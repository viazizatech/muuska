<?php
namespace muuska\controller\param;

interface ControllerParam
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getValue();
    
    /**
     * @return object
     */
    public function getObject();
    
    /**
     * @return bool
     */
    public function hasObject();
}