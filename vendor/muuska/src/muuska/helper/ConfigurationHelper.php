<?php
namespace muuska\helper;

class ConfigurationHelper extends AbstractHelper
{
    /**
     * @var string
     */
    protected $name = 'configuration';
    
    /**
     * @var \muuska\controller\ControllerInput
     */
    protected $input;
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     */
    public function __construct(\muuska\controller\ControllerInput $controllerInput){
        $this->input = $controllerInput;
    }
}