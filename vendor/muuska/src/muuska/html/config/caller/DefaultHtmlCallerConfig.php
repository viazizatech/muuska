<?php
namespace muuska\html\config\caller;
use muuska\util\AbstractExtraDataProvider;

class DefaultHtmlCallerConfig extends AbstractExtraDataProvider implements HtmlCallerConfig{
    /**
     * @var \muuska\renderer\HtmlContentRenderer
     */
    protected $renderer;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $callerInstance;
    
    /**
     * @var bool
     */
    protected $visible;
    
    /**
     * @var bool
     */
    protected $visibilityChanged;
    
    /**
     * @var array
     */
    protected $attributes = array();
    
    /**
     * @var string[]
     */
    protected $classes = array();
    
    /**
     * @var bool
     */
    protected $onlyContentEnabled;
    
    /**
     * @var array
     */
    protected $styleAttributes;
    
    /**
     * @var string[]
     */
    protected $excludedAttributes;
    
    /**
     * @var string[]
     */
    protected $excludedStyleAttributes;
    
    /**
     * @var string[]
     */
    protected $excludedClasses;
    
    /**
     * @var array
     */
    protected $disabledAreas = array();
    
    /**
     * @var string
     */
    protected $preferredTag;
    
    /**
     * @param \muuska\html\HtmlContent $callerInstance
     * @param string $stringClasses
     * @param array $styleAttributes
     * @param array $attributes
     * @param string[] $excludedAttributes
     * @param string[] $excludedStyleAttributes
     * @param string[] $excludedClasses
     */
    public function __construct(\muuska\html\HtmlContent $callerInstance, $stringClasses = null, $styleAttributes = null, $attributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null){
        $this->setCallerInstance($callerInstance);
        $this->setClasses($this->getClassesFromString($stringClasses));
        $this->setStyleAttributes($styleAttributes);
        $this->setAttributes($attributes);
        $this->setExcludedAttributes($excludedAttributes);
        $this->setExcludedStyleAttributes($excludedStyleAttributes);
        $this->setExcludedClasses($excludedClasses);
    }
    
    /**
     * @param array $classes
     */
    public function addClasses($classes) {
        if(is_array($classes)){
            foreach ($classes as $class) {
                $this->addClass($class);
            }
        }
    }
    
    /**
     * @param string $string
     */
    public function addClassesFromString($string) {
        $classes = $this->getClassesFromString($string);
        foreach ($classes as $class) {
            $this->addClass($class);
        }
    }
    
    /**
     * @param string $string
     * @return array
     */
    public function getClassesFromString($string) {
        return explode(' ', $string);
    }
    
    /**
     * @param string $class
     */
    public function addClass($class) {
        if (!$this->hasClass($class)) {
            $this->classes[] = $class;
        }
    }
    
    /**
     * @param string $class
     * @return bool
     */
    public function hasClass($class) {
        return in_array($class, $this->classes);
    }
    
    /**
     * @param array $attributes
     */
    public function addAttributes($attributes) {
        if(is_array($attributes)){
            foreach ($attributes as $key => $value) {
                $this->addAttribute($key, $value);
            }
        }
    }
    
    /**
     * @param string $name
     * @param mixed $value
     */
    public function addAttribute($name, $value) {
        $this->setAttribute($name, $value);
    }
    
    /**
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name) {
        return $this->hasAttribute($name) ? $this->attributes[$name] : null;
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name) {
        return array_key_exists($name, $this->attributes);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\config\caller\HtmlCallerConfig::hasPreferredTag()
     */
    public function hasPreferredTag()
    {
        return !empty($this->preferredTag);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\config\caller\HtmlCallerConfig::isAreaDisabled()
     */
    public function isAreaDisabled($areaName)
    {
        return in_array($areaName, $this->disabledAreas);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\config\caller\HtmlCallerConfig::hasRenderer()
     */
    public function hasRenderer()
    {
        return ($this->renderer !== null);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\config\caller\HtmlCallerConfig::isAttributeExcluded()
     */
    public function isAttributeExcluded($name)
    {
        return (is_array($this->excludedAttributes) && in_array($name, $this->excludedAttributes));
    }
    
    /**
     * @return \muuska\renderer\HtmlContentRenderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getCallerInstance()
    {
        return $this->callerInstance;
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @return boolean
     */
    public function isVisibilityChanged()
    {
        return $this->visibilityChanged;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @return boolean
     */
    public function isOnlyContentEnabled()
    {
        return $this->onlyContentEnabled;
    }

    /**
     * @return array
     */
    public function getStyleAttributes()
    {
        return $this->styleAttributes;
    }

    /**
     * @return array
     */
    public function getExcludedAttributes()
    {
        return $this->excludedAttributes;
    }

    /**
     * @return array
     */
    public function getExcludedStyleAttributes()
    {
        return $this->excludedStyleAttributes;
    }

    /**
     * @return array
     */
    public function getExcludedClasses()
    {
        return $this->excludedClasses;
    }

    /**
     * @return string[]
     */
    public function getDisabledAreas()
    {
        return $this->disabledAreas;
    }

    /**
     * @return string
     */
    public function getPreferredTag()
    {
        return $this->preferredTag;
    }

    /**
     * @param \muuska\renderer\HtmlContentRenderer $renderer
     */
    public function setRenderer(?\muuska\renderer\HtmlContentRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param \muuska\html\HtmlContent $callerInstance
     */
    public function setCallerInstance(\muuska\html\HtmlContent $callerInstance)
    {
        $this->callerInstance = $callerInstance;
    }

    /**
     * @param boolean $visible
     */
    public function setVisible($visible)
    {
        $this->visibilityChanged = true;
        $this->visible = $visible;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = array();
        $this->addAttributes($attributes);
    }

    /**
     * @param array $classes
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;
    }

    /**
     * @param boolean $onlyContentEnabled
     */
    public function setOnlyContentEnabled($onlyContentEnabled)
    {
        $this->onlyContentEnabled = $onlyContentEnabled;
    }

    /**
     * @param array $styleAttributes
     */
    public function setStyleAttributes($styleAttributes)
    {
        $this->styleAttributes = $styleAttributes;
    }

    /**
     * @param array $excludedAttributes
     */
    public function setExcludedAttributes($excludedAttributes)
    {
        $this->excludedAttributes = $excludedAttributes;
    }

    /**
     * @param array $excludedStyleAttributes
     */
    public function setExcludedStyleAttributes($excludedStyleAttributes)
    {
        $this->excludedStyleAttributes = $excludedStyleAttributes;
    }

    /**
     * @param array $excludedClasses
     */
    public function setExcludedClasses($excludedClasses)
    {
        $this->excludedClasses = $excludedClasses;
    }

    /**
     * @param string[] $disabledAreas
     */
    public function setDisabledAreas($disabledAreas)
    {
        $this->disabledAreas = array();
        $this->addDisabledAreas($disabledAreas);
    }
    
    /**
     * @param string $area
     */
    public function addDisabledArea($area) {
        $this->disabledAreas[] = $area;
    }
    
    /**
     * @param string[] $disabledAreas
     */
    public function addDisabledAreas($disabledAreas) {
        if (is_array($disabledAreas)) {
            foreach ($disabledAreas as $area) {
                $this->addDisabledArea($area);
            }
        }
    }

    /**
     * @param string $preferredTag
     */
    public function setPreferredTag($preferredTag)
    {
        $this->preferredTag = $preferredTag;
    }
}