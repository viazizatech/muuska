<?php
namespace muuska\controller\param;

use muuska\util\App;

class DefaultControllerParamResolver implements ControllerParamResolver
{
    /**
     * @var ControllerParamParser[]
     */
    protected $parsers;
    
    /**
     * @var ControllerParam[]
     */
    protected $params = array();
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\controller\DefaultControllerResult $controllerResult
     * @param ControllerParamParser[] $parsers
     */
    public function __construct(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult, $parsers = array()){
        $this->setParsers($parsers);
        $this->resolveParams($controllerInput, $controllerResult);
    }
    
    /**
     * @param ControllerParamParser $parser
     */
    public function addParser(ControllerParamParser $parser) {
        $this->parsers[$parser->getName()] = $parser;
    }
    
    /**
     * @param ControllerParamParser[] $parsers
     */
    public function addParsers($parsers) {
        if(is_array($parsers)){
            foreach ($parsers as $parser) {
                $this->addParser($parser);
            }
        }
    }
    
    /**
     * @param ControllerParamParser[] $parsers
     */
    public function setParsers($parsers) {
        $this->parsers = array();
        $this->addParsers($parsers);
    }
    
    /**
     * @param ControllerParam $param
     */
    public function addParam(ControllerParam $param) {
        $this->params[$param->getName()] = $param;
    }
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\controller\DefaultControllerResult $controllerResult
     */
    public function resolveParams(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult){
        foreach($this->parsers as $parser){
            $param = $parser->createParam($controllerInput, $controllerResult);
            if($param !== null){
                $this->addParam($param);
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamResolver::hasParser()
     */
    public function hasParser($name){
        return isset($this->parsers[$name]);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamResolver::getParser()
     */
    public function getParser($name){
        return $this->hasParser($name) ? $this->parsers[$name] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamResolver::getParsers()
     */
    public function getParsers(){
        return $this->parsers;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamResolver::hasParam()
     */
    public function hasParam($name){
        return isset($this->params[$name]);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamResolver::getParam()
     */
    public function getParam($name){
        return $this->hasParam($name) ? $this->params[$name] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamResolver::getParams()
     */
    public function getParams(){
        return $this->params;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamResolver::createUrlCreator()
     */
    public function createUrlCreator(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\ControllerResult $controllerResult){
        $initialParams = array();
        foreach ($this->params as $param) {
            $initialParams[$param->getName()] = $param->getValue();
        }
        return App::urls()->createDefaultControllerUrl($controllerInput, $initialParams);
    }
}