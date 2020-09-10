<?php
namespace muuska\html\listing;
use muuska\html\HtmlElement;
class ListField extends HtmlElement{
    /**
     * @var string
     */
    protected $label;
    
    /**
     * @var \muuska\renderer\value\ValueRenderer
     */
    protected $valueRenderer;
    
    /**
     * @param string $name
     * @param \muuska\renderer\value\ValueRenderer $valueRenderer
     * @param string $label
     */
    public function __construct($name, \muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null) {
        $this->setName($name);
        $this->setLabel($label);
        $this->setValueRenderer($valueRenderer);
    }
    
    /**
     * @return boolean
     */
    public function hasValueRenderer() {
        return ($this->valueRenderer !== null);
    }
    
    /**
     * @param mixed $containerData
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function renderValue($containerData, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
        $content = '';
        if($this->hasValueRenderer()){
            $content = $this->valueRenderer->renderValue($containerData, $globalConfig, $currentCallerConfig);
        }
        return $content;
    }
    
    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * @return \muuska\renderer\value\ValueRenderer
     */
    public function getValueRenderer()
    {
        return $this->valueRenderer;
    }
    
    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
    
    /**
     * @param \muuska\renderer\value\ValueRenderer $valueRenderer
     */
    public function setValueRenderer(?\muuska\renderer\value\ValueRenderer $valueRenderer)
    {
        $this->valueRenderer = $valueRenderer;
    }
}