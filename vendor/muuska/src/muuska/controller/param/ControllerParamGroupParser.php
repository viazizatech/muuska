<?php
namespace muuska\controller\param;

interface ControllerParamGroupParser extends ControllerParamParser
{
    /**
     * @return bool
     */
    public function isRequired();
    
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return ControllerParam
     */
    public function createParam(\muuska\controller\ControllerInput $controllerInput);
}