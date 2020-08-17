<?php
namespace muuska\controller\param;

interface ControllerParamResolver
{
    /**
     * @param string $name
     * @return bool
     */
    public function hasParser($name);
    
    /**
     * @param string $name
     * @return ControllerParamParser
     */
    public function getParser($name);
    
    /**
     * @return ControllerParamParser[]
     */
    public function getParsers();
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasParam($name);
    
    /**
     * @param string $name
     * @return ControllerParam
     */
    public function getParam($name);
    
    /**
     * @return ControllerParam[]
     */
    public function getParams();
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\controller\DefaultControllerResult $controllerResult
     * @return \muuska\url\ControllerUrlCreator
     */
    public function createUrlCreator(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\ControllerResult $controllerResult);
}