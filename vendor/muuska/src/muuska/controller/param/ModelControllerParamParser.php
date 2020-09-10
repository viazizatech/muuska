<?php
namespace muuska\controller\param;

class ModelControllerParamParser extends DefaultControllerParamParser
{
    /**
     * @var \muuska\model\ModelDefinition
     */
    protected $modelDefinition;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $name
     * @param boolean $required
     * @param array $definition
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, $name, $required = false, $definition = null){
        parent::__construct($name, $required, $definition);
        $this->modelDefinition = $modelDefinition;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\DefaultControllerParamParser::createParam()
     */
    public function createParam(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult, $finalName = null){
        return $this->autoCreateParam($controllerInput, $controllerResult, $finalName, $this->modelDefinition);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\DefaultControllerParamParser::getObjectPresentation()
     */
    protected function getObjectPresentation(object $objet) {
        return $this->modelDefinition->getModelPresentation($objet);
    }
}