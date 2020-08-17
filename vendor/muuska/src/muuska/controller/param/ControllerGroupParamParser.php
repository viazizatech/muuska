<?php
namespace muuska\controller\param;

class ControllerGroupParamParser extends DefaultControllerParamParser
{
    /**
     * @var ControllerParamParser[]
     */
    protected $children = array();
    
    /**
     * @param string $name
     * @param boolean $required
     * @param ControllerParamParser[] $children
     * @param array $definition
     */
    public function __construct($name, $required = false, $children = array(), $definition = null){
        parent::__construct($name, $required);
        $this->setChildren($children);
    }
    
    /**
     * @return ControllerParamParser[]
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * @param ControllerParamParser[] $children
     */
    public function setChildren($children)
    {
        $this->children = array();
        $this->addChildren($children);
    }
    
    /**
     * @param ControllerParamParser[] $children
     */
    public function addChildren($children)
    {
        if(is_array($children)){
            foreach ($children as $child) {
                $this->addChild($child);
            }
        }
    }
    
    /**
     * @param ControllerParamParser $child
     */
    public function addChild($child)
    {
        $this->children[$child->getName()] = $child;
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasChild($name)
    {
        return isset($this->children[$name]);
    }
    
    /**
     * @param string $name
     * @return \muuska\controller\param\ControllerParamParser
     */
    public function getChild($name)
    {
        return $this->hasChild($name) ? $this->children[$name] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParamParser::createParam()
     */
    public function createParam(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult, $finalName = null){
        $hasValue = false;
        $param = null;
        foreach ($this->children as $child) {
            $hasValue = $controllerInput->hasQueryParam($child->getName());
            if($hasValue){
                $param = $child->createParam($controllerInput, $controllerResult, null);
                /*$param = $child->createParam($controllerInput, $controllerResult, $this->getParamName($finalName));*/
                break;
            }
        }
        if(!$hasValue){
            $this->onValueNotFound($controllerInput, $controllerResult);
        }
        return $param;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\DefaultControllerParamParser::formatHelperList()
     */
    public function formatHelperList(\muuska\helper\ModelListHelper $helper){
        $paramResolver = $helper->getParamResolver();
        foreach ($this->children as $child) {
            if ($paramResolver->hasParam($child->getName())) {
                $child->formatHelperList($helper);
            }else{
                $childDefinition = $child->getDefinition();
                if (isset($childDefinition['modelField']) && !empty($childDefinition['modelField']) && (!isset($childDefinition['hiddenInlist']) && $childDefinition['hiddenInlist'])) {
                    $helper->addExcludedField($childDefinition['modelField']);
                }
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\DefaultControllerParamParser::formatHelperForm()
     */
    public function formatHelperForm(\muuska\helper\ModelFormHelper $helper){
        $paramResolver = $helper->getParamResolver();
        foreach ($this->children as $child) {
            if ($paramResolver->hasParam($child->getName())) {
                $child->formatHelperForm($helper);
            }else{
                $childDefinition = $child->getDefinition();
                if (isset($childDefinition['modelField']) && !empty($childDefinition['modelField']) && (!isset($childDefinition['hiddenInForm']) && $childDefinition['hiddenInForm'])) {
                    $helper->addExcludedField($childDefinition['modelField']);
                }
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\DefaultControllerParamParser::formatHelperView()
     */
    public function formatHelperView(\muuska\helper\ModelCrudViewHelper $helper){
        $paramResolver = $helper->getParamResolver();
        foreach ($this->children as $child) {
            if ($paramResolver->hasParam($child->getName())) {
                $child->formatHelperView($helper);
            }else{
                $childDefinition = $child->getDefinition();
                if (isset($childDefinition['modelField']) && !empty($childDefinition['modelField']) && (!isset($childDefinition['hiddenInView']) && $childDefinition['hiddenInView'])) {
                    $helper->addExcludedField($childDefinition['modelField']);
                }
            }
        }
    }
}