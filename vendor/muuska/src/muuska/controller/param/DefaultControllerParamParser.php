<?php
namespace muuska\controller\param;

use muuska\util\App;

class DefaultControllerParamParser implements ControllerParamParser
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var bool
     */
    protected $required;
    
    /**
     * @var array
     */
    protected $definition;
    
    /**
     * @param string $name
     * @param boolean $required
     * @param array $definition
     */
    public function __construct($name, $required = false, $definition = null){
        $this->name = $name;
        $this->required = $required;
        $this->definition = $definition;
    }
        
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamParser::getName()
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamParser::isRequired()
     */
    public function isRequired(){
        return $this->required;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamParser::getDefinition()
     */
    public function getDefinition(){
        return $this->definition;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamParser::createParam()
     */
    public function createParam(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult, $finalName = null){
        return $this->autoCreateParam($controllerInput, $controllerResult, $finalName);
    }
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\controller\DefaultControllerResult $controllerResult
     * @param string $finalName
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @return \muuska\controller\param\DefaultControllerParam
     */
    protected function autoCreateParam(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult, $finalName = null, \muuska\model\ModelDefinition $modelDefinition = null){
        $value = $this->getValue($controllerInput, $controllerResult);
        $result = null;
        if($value !== null){
            $object = ($modelDefinition !== null) ? $this->getObject($controllerInput, $controllerResult, $modelDefinition, $value) : null;
            $result = App::controllers()->createDefaultControllerParam($this->getParamName($finalName), $value, $object);
        }
        return $result;
    }
    
    /**
     * @param string $finalName
     * @return string
     */
    protected function getParamName($finalName = null){
        return empty($finalName) ? $this->getName() : $finalName;
    }
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\controller\DefaultControllerResult $controllerResult
     * @return mixed
     */
    protected function getValue(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult){
        $value = null;
        if($controllerInput->hasQueryParam($this->name)){
            $value = $controllerInput->getQueryParam($this->name);
        }else{
            $this->onValueNotFound($controllerInput, $controllerResult);
        }
        return $value;
    }
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\controller\DefaultControllerResult $controllerResult
     */
    protected function onValueNotFound(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult){
        if($this->isRequired()){
            $controllerResult->addError(sprintf($controllerInput->getFrameworkError('%s is required'), $this->name));
        }
    }
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\controller\DefaultControllerResult $controllerResult
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param mixed $value
     * @return object
     */
    protected function getObject(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult, \muuska\model\ModelDefinition $modelDefinition, $value)
    {
        $object = null;
        $object = $controllerInput->getDAO($modelDefinition)->getById($value, $controllerInput->createSelectionConfig());
        if(($object === null)){
            $controllerResult->addError(sprintf($controllerInput->getFrameworkError('Invalid value for %s'), $this->name));
        }
        return $object;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamParser::formatHelperForm()
     */
    public function formatHelperForm(\muuska\helper\ModelFormHelper $helper){
        $paramResolver = $helper->getParamResolver();
        if($paramResolver->hasParam($this->name)){
            $param = $paramResolver->getParam($this->name);
            
            if (!$helper->isUpdate() && empty($helper->getTitle()) && isset($this->definition['addTitleFormat']) && !empty($this->definition['addTitleFormat']) && $param->hasObject()) {
                $helper->setTitle(sprintf($this->definition['addTitleFormat'], $this->getObjectPresentation($param->getObject())));
            }
            if(isset($this->definition['modelField']) && !empty($this->definition['modelField'])){
                if(!isset($this->definition['hiddenInForm']) || $this->definition['hiddenInForm']){
                    $helper->addExcludedField($this->definition['modelField']);
                }
                $helper->addDefaultValue($this->definition['modelField'], $param->getValue());
            }
            if(isset($this->definition['initialValues']) && !empty($this->definition['initialValues'])){
                foreach ($this->definition['initialValues'] as $key => $value) {
                    $helper->addExcludedField($key);
                    $helper->addDefaultValue($key, $value);
                }
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamParser::formatHelperView()
     */
    public function formatHelperView(\muuska\helper\ModelCrudViewHelper $helper){
        $paramResolver = $helper->getParamResolver();
        if($paramResolver->hasParam($this->name)){
            if(isset($this->definition['modelField']) && !empty($this->definition['modelField'])){
                if(!isset($this->definition['hiddenInView']) || $this->definition['hiddenInView']){
                    $helper->addExcludedField($this->definition['modelField']);
                }
            }
            if(isset($this->definition['initialValues']) && !empty($this->definition['initialValues'])){
                $fields = array_keys($this->definition['initialValues']);
                foreach ($fields as $field) {
                    $helper->addExcludedField($field);
                }
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamParser::formatHelperList()
     */
    public function formatHelperList(\muuska\helper\ModelListHelper $helper){
        $paramResolver = $helper->getParamResolver();
        $selectionConfig = $helper->getSelectionConfig();
        if($paramResolver->hasParam($this->name)){
            $param = $paramResolver->getParam($this->name);
            if (empty($helper->getTitle()) && isset($this->definition['listTitleFormat']) && !empty($this->definition['listTitleFormat']) && $param->hasObject()) {
                $helper->setTitle(sprintf($this->definition['listTitleFormat'], $this->getObjectPresentation($param->getObject())));
            }
            if(isset($this->definition['modelField']) && !empty($this->definition['modelField'])){
                if(!isset($this->definition['hiddenInlist']) || $this->definition['hiddenInlist']){
                    $helper->addExcludedField($this->definition['modelField']);
                }
                if(!isset($this->definition['noRestriction']) || !$this->definition['noRestriction']){
                    $selectionConfig->addRestrictionFieldFromParams($this->definition['modelField'], $param->getValue());
                }
            }
            if(isset($this->definition['initialValues']) && !empty($this->definition['initialValues'])){
                foreach ($this->definition['initialValues'] as $key => $value) {
                    $helper->addExcludedField($key);
                    $selectionConfig->addRestrictionFieldFromParams($key, $value);
                }
            }
            if(isset($this->definition['additionalRestrictions']) && !empty($this->definition['additionalRestrictions'])){
                foreach ($this->definition['additionalRestrictions'] as $key => $restriction) {
                    $keyStr = is_string($key) ? $key : null;
                    $selectionConfig->addRestrictionField($restriction, $keyStr);
                }
            }
        }
    }
    
    /**
     * @param object $objet
     * @return string
     */
    protected function getObjectPresentation(object $objet) {
        return $objet->__toString();
    }
}