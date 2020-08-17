<?php
namespace muuska\controller\param;

interface ControllerParamParser
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return bool
     */
    public function isRequired();
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\controller\DefaultControllerResult $controllerResult
     * @param string $finalName
     * @return ControllerParam
     */
    public function createParam(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult, $finalName = null);

    /**
     * @param \muuska\helper\ModelFormHelper $helper
     */
    public function formatHelperForm(\muuska\helper\ModelFormHelper $helper);
    
    /**
     * @param \muuska\helper\ModelCrudViewHelper $helper
     */
    public function formatHelperView(\muuska\helper\ModelCrudViewHelper $helper);
    
    /**
     * @param \muuska\helper\ModelListHelper $helper
     */
    public function formatHelperList(\muuska\helper\ModelListHelper $helper);
    
    /**
     * @return array
     */
    public function getDefinition();
}