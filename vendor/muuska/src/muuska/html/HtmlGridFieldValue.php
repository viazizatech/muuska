<?php
namespace muuska\html;
class HtmlGridFieldValue extends HtmlFieldValue{
    /**
     * @var string
     */
    protected $componentName = 'grid_field_value';
    
    /**
     * @var string[]
     */
    protected $labelWidthClasses = array();
    
    /**
     * @var string[]
     */
    protected $inputWidthClasses = array();
    
    /**
     * @param string $name
     * @param mixed $value
     * @param string $htmlValue
     * @param \muuska\html\HtmlContent $label
     * @param \muuska\renderer\value\ValueRenderer $valueRenderer
     */
    public function __construct($name, $value, $htmlValue = null, \muuska\html\HtmlContent $label = null, \muuska\renderer\value\ValueRenderer $valueRenderer = null) {
        parent::__construct($name, $value, $htmlValue, $label, $valueRenderer);
        $this->setLabelWidthClasses(array('col-lg-3'));
        $this->setInputWidthClasses(array('col-lg-6'));
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $class
     * @return string
     */
    public function renderLabelWithGridClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $class = null) {
        return $this->renderLabel($globalConfig, $callerConfig, $this->createCallerConfig($this->concatTwoStrings($class, implode(' ', $this->getFinalWitdhClasses($globalConfig, $this->labelWidthClasses)))));
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function renderValueWithGridClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $prefix = '', $suffix = '') {
        return '<div'.$this->drawClassesFromList($this->getFinalWitdhClasses($globalConfig, $this->inputWidthClasses), null, true, true).'>'.$prefix.$this->renderValue($globalConfig, $currentCallerConfig).$suffix.'</div>';
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param string[] $classes
     * @return string[]
     */
    public function getFinalWitdhClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, $classes) {
        $result = array();
        $theme = $globalConfig->getTheme();
        if(!empty($classes)){
            if($theme !== null){
                foreach ($classes as $class) {
                    $result[] = $theme->getWidthClass($class);
                }
            }else{
                $result = $classes;
            }
        }
        return $result;
    }
    
    /**
     * @return string[]
     */
    public function getLabelWidthClasses()
    {
        return $this->labelWidthClasses;
    }

    /**
     * @return string[]
     */
    public function getInputWidthClasses()
    {
        return $this->inputWidthClasses;
    }
    
    /**
     * @param string $class
     */
    public function addLabelWidthClass($class) {
        if (!$this->hasLabelWidthClass($class)) {
            $this->labelWidthClasses[] =$class;
        }
    }
    
    /**
     * @param string[] $classes
     */
    public function addLabelWidthClasses($classes) {
        if (is_array($classes)) {
            foreach ($classes as $class) {
                $this->addLabelWidthClass($class);
            }
        }
    }
    
    /**
     * @param string $class
     * @return boolean
     */
    public function hasLabelWidthClass($class) {
        return in_array($class, $this->labelWidthClasses);
    }
    
    /**
     * @param string $string
     */
    public function addLabelWidthClassesFromString($string) {
        $classes = $this->getClassesFromString($string);
        foreach ($classes as $class) {
            $this->addLabelWidthClass($class);
        }
    }
    
    /**
     * @param string[] $classes
     */
    public function setLabelWidthClasses($classes)
    {
        $this->labelWidthClasses = array();
        $this->addLabelWidthClasses($classes);
    }
    
    /**
     * @param string $class
     */
    public function addInputWidthClass($class) {
        if (!$this->hasInputWidthClass($class)) {
            $this->inputWidthClasses[] =$class;
        }
    }
    
    /**
     * @param string[] $classes
     */
    public function addInputWidthClasses($classes) {
        if (is_array($classes)) {
            foreach ($classes as $class) {
                $this->addInputWidthClass($class);
            }
        }
    }
    
    /**
     * @param string $class
     * @return boolean
     */
    public function hasInputWidthClass($class) {
        return in_array($class, $this->inputWidthClasses);
    }
    
    /**
     * @param string $string
     */
    public function addInputWidthClassesFromString($string) {
        $classes = $this->getClassesFromString($string);
        foreach ($classes as $class) {
            $this->addInputWidthClasses($class);
        }
    }
    
    /**
     * @param string[] $classes
     */
    public function setInputWidthClasses($classes)
    {
        $this->inputWidthClasses = array();
        $this->addInputWidthClasses($classes);
    }
}