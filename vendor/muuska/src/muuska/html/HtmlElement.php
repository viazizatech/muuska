<?php
namespace muuska\html;
use muuska\util\App;

class HtmlElement extends HtmlComponent{
    /**
	 * @var bool
	 */
	protected $visible = true;
	
	/**
	 * @var string
	 */
	protected $id;
	
	/**
	 * @var array
	 */
	protected $attributes = array();
	
	/**
	 * @var string[]
	 */
	protected $classes = array();
	
	/**
	 * @var array
	 */
	protected $styleAttributes = array();
	
	/**
	 * @var bool
	 */
	protected $onlyContentEnabled;
	
	/**
	 * @param string[] $classes
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
	 */
	public function removeClass($class) {
	    $this->classes = App::getArrayTools()->removeValue($this->classes, $class);
	}
	
	/**
	 * @param string $class
	 * @return bool
	 */
	public function hasClass($class) {
		return in_array($class, $this->classes);
	}
	
	/**
	 * @param string[] $classes
	 */
	public function setClasses($classes){
	    $this->classes = array();
	    $this->addClasses($classes);
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
	 * @param array $attributes
	 */
	public function setAttributes($attributes) {
	    $this->attributes = array();
	    $this->addAttributes($attributes);
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
	 */
	public function removeAttribute($name) {
	    if($this->hasAttribute($name)){
	        unset($this->attributes[$name]);
	    }
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
	 * @param string $name
	 * @param mixed $value
	 */
	public function addStyleAttribute($name, $value) {
	    $this->setStyleAttribute($name, $value);
	}
	
	/**
	 * @param array $attributes
	 */
	public function addStyleAttributes($attributes) {
	    if (!is_array($attributes)) {
	        foreach ($attributes as $name => $value) {
	            $this->addStyleAttribute($name, $value);
	        }
	    }
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setStyleAttribute($name, $value) {
	    $this->styleAttributes[$name] = $value;
	}
	
	/**
	 * @param string $name
	 */
	public function removeStyleAttribute($name) {
	    if($this->hasStyleAttribute($name)){
	        unset($this->styleAttributes[$name]);
	    }
	}
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getStyleAttribute($name) {
	    return $this->hasStyleAttribute($name) ? $this->styleAttributes[$name] : null;
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasStyleAttribute($name) {
	    return array_key_exists($name, $this->styleAttributes);
	}
	
	/**
	 * @param array $styleAttributes
	 */
	public function setStyleAttributes($styleAttributes)
	{
	    $this->styleAttributes = array();
	    $this->addStyleAttributes($styleAttributes);
	}
	
	/**
	 * @param string $string1
	 * @param string $string2
	 * @param bool $addSpace
	 * @return string
	 */
	public function concatTwoStrings($string1, $string2, $addSpace = false) {
	    return $this->concatStrings(array($string1, $string2), $addSpace);
	}
	
	/**
	 * @param array $array
	 * @param bool $addSpace
	 * @return string
	 */
	public function concatStrings($array, $addSpace = true) {
	    $result = '';
	    $first = true;
	    foreach ($array as $value) {
	        if(!empty($value)){
	            if(!$first){
	                $result .= ' ';
	            }
	            $result .= $value;
	            $first = false;
	        }
	    }
	    return $this->getStringLeftWithSpace($result, $addSpace);
	}
	
	/**
	 * @param string $initializer
	 * @param bool $useGroup
	 * @param string $groupName
	 */
	public function setJsInitializationRequired($initializer, $useGroup = false, $groupName = ''){
		$this->addAttribute('data-init-required', 1);
		$this->addAttribute('data-initializer', $initializer);
		if($useGroup){
			$groupName = empty($groupName) ? $this->getComponentName() : $groupName;
			$this->addAttribute('data-init-group', $groupName);
			$this->addClass($groupName);
			$this->addAttribute('data-init-selector', '.'.$groupName);
		}
	}
	
	/**
	 * @param bool $condition
	 * @param string $trueValue
	 * @param string $falseValue
	 * @param bool $addSpace
	 * @return string
	 */
	public function getStringFromCondition($condition, $trueValue, $falseValue = '', $addSpace = false) {
	    $result = $condition ? $trueValue : $falseValue;
	    return $this->getStringLeftWithSpace($result, $addSpace);
	}
	
	/**
	 * @param string $string
	 * @param bool $addSpace
	 * @return string
	 */
	public function getStringLeftWithSpace($string, $addSpace = true){
	    $space = $addSpace ? ' ' : '';
	    return empty($string) ? $string : $space . $string;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return array
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $result = array();
	    if(!empty($this->id)){
	        $result['id'] = $this->id;
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return array
	 */
	protected function getOtherStyleAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $result = array();
	    $visibleValue = $this->visible;
	    if(($callerConfig !== null) && $callerConfig->isVisibilityChanged()){
	        $visibleValue = $callerConfig->isVisible();
	    }
	    if(!$visibleValue){
	        $result['display'] = 'none';
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return array
	 */
	protected function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    return array();
	}
	
	/**
	 * @param string $tag
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param array $excludedAttributes
	 * @param array $excludedStyleAttributes
	 * @param array $excludedClasses
	 * @return string
	 */
	public function drawStartTag($tag, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null) {
	    $result = '';
	    $onlyContentEnabled = $this->isOnlyContentEnabled() || (($callerConfig != null) && $callerConfig->isOnlyContentEnabled());
	    if(!$onlyContentEnabled){
	        $result = '<'.$this->getFinalTag($tag, $callerConfig).$this->drawAllAttributes($globalConfig, $callerConfig, $stringClasses, $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses).'>';
	    }
	    return $result;
	}
	
	/**
	 * @param string $tag
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string
	 */
	public function drawEndTag($tag, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig) {
	    $result = '';
	    $onlyContentEnabled = $this->isOnlyContentEnabled() || (($callerConfig != null) && $callerConfig->isOnlyContentEnabled());
	    if(!$onlyContentEnabled){
	        $result = '</'.$this->getFinalTag($tag, $callerConfig).'>';
	    }
	    return $result;
	}
	
	/**
	 * @param string $tag
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string
	 */
	protected function getFinalTag($tag, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return (($callerConfig !== null) && $callerConfig->hasPreferredTag()) ? $callerConfig->getPreferredTag() : $tag;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param array $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param array $excludedAttributes
	 * @param array $excludedStyleAttributes
	 * @param array $excludedClasses
	 * @param bool $addSpace
	 * @return string
	 */
	public function drawAllAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null, $addSpace = true) {
	    $result = '';
	    if((!is_array($excludedAttributes) || !in_array('class', $excludedAttributes)) && (($callerConfig === null) || !$callerConfig->isAttributeExcluded('class'))){
	        $result .= $this->drawAllClasses($globalConfig, $callerConfig, false, true, $stringClasses, $excludedClasses);
	    }
	    
	    $result = $this->concatTwoStrings($result, $this->drawAttributes($globalConfig, $callerConfig, false, $newAttributes, $excludedAttributes));
	    if((!is_array($excludedAttributes) || !in_array('style', $excludedAttributes)) && (($callerConfig === null) || !$callerConfig->isAttributeExcluded('style'))){
	        $result = $this->concatTwoStrings($result, $this->drawAllStyleAttributes($globalConfig, $callerConfig, true, false, $newStyleAttributes, $excludedStyleAttributes));
	    }
	    return $this->getStringLeftWithSpace($result, $addSpace);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param bool $addSpace
	 * @param array $newAttributes
	 * @param array $excludedAttributes
	 * @return string
	 */
	public function drawAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $addSpace = true, $newAttributes = null, $excludedAttributes = null) {
	    $allAttributes = array();
	    $otherAttributes = $this->getOtherAttributes($globalConfig, $callerConfig);
	    $finalExcludedAttributes = $excludedAttributes;
	    if(is_array($otherAttributes)){
	        $allAttributes = array_merge($allAttributes, $otherAttributes);
	    }
	    if(is_array($newAttributes)){
	        $allAttributes = array_merge($allAttributes, $newAttributes);
	    }
	    if($callerConfig !== null){
	        $callerAttributes = $callerConfig->getAttributes();
	        if(is_array($callerAttributes)){
	            $allAttributes = array_merge($allAttributes, $callerAttributes);
	        }
	        $callerExcludedAttributes = $callerConfig->getExcludedAttributes();
	        if(is_array($callerExcludedAttributes)){
	            $finalExcludedAttributes = is_array($finalExcludedAttributes) ? array_merge($finalExcludedAttributes, $callerExcludedAttributes) : $callerExcludedAttributes;
	        }
	    }
	    $allAttributes = array_merge($allAttributes, $this->attributes);
	    return $this->drawAttributesFromList($allAttributes, $addSpace, $finalExcludedAttributes);
	}
	
	/**
	 * @param array $attributes
	 * @param bool $addSpace
	 * @param array $excludedAttributes
	 * @return string
	 */
	protected function drawAttributesFromList($attributes, $addSpace = true, $excludedAttributes = null) {
	    $result = '';
	    $first = true;
	    foreach ($attributes as $key => $value) {
	        if(!is_array($excludedAttributes) || !in_array($key, $excludedAttributes)){
	            if(!$first){
	                $result .= ' ';
	            }
	            $result .= $key . (($value ===null) ? '' : '="' . $value . '"');
	            $first = false;
	        }
	    }
	    return $this->getStringLeftWithSpace($result);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param boolean $addSpace
	 * @param boolean $addClassAttribute
	 * @param string $stringClasses
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function drawAllClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $addSpace = true, $addClassAttribute = false, $stringClasses = null, $excludedClasses = null){
	    $otherClasses = $this->getOtherClasses($globalConfig, $callerConfig);
	    return $this->drawClassesFromMultipleArray(array($otherClasses, $this->classes), $callerConfig, $addSpace, $addClassAttribute, $stringClasses, $excludedClasses);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param boolean $addSpace
	 * @param boolean $addClassAttribute
	 * @param string $stringClasses
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function drawOriginalClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $addSpace = true, $addClassAttribute = false, $stringClasses = null, $excludedClasses = null) {
	    return $this->drawClassesFromList($this->classes, $callerConfig, $addSpace, $addClassAttribute, $stringClasses, $excludedClasses);
	}
	
	/**
	 * @param string[][] $allClasses
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param boolean $addSpace
	 * @param boolean $addClassAttribute
	 * @param string $stringClasses
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function drawClassesFromMultipleArray($allClasses, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $addSpace = true, $addClassAttribute = false, $stringClasses = null, $excludedClasses = null) {
	    $finalClasses = array();
	    $finalExcludedClasses = is_array($excludedClasses) ? $excludedClasses : array();
	    if(!empty($stringClasses)){
	        $allClasses[] = $this->getClassesFromString($stringClasses);
	    }
	    if($callerConfig !== null){
	        $allClasses[] = $callerConfig->getClasses();
	        $callerExcludedClasses = $callerConfig->getExcludedClasses();
	        $finalExcludedClasses = is_array($callerExcludedClasses) ? array_merge($finalExcludedClasses, $callerExcludedClasses) : $finalExcludedClasses;
	    }
	    $finalExcludedClasses = !is_array($finalExcludedClasses) ? array() : $finalExcludedClasses;
	    foreach ($allClasses as $classes) {
	        if(!empty($classes)){
	            foreach ($classes as $class) {
	                if(!in_array($class, $finalExcludedClasses)){
	                    $finalClasses[] = $class;
	                }
	            }
	        }
	    }
	    return $this->drawClassFromString(implode(' ', array_unique($finalClasses)), $addSpace, $addClassAttribute);
	}
	
	/**
	 * @param array $classes
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param bool $addSpace
	 * @param bool $addClassAttribute
	 * @param string $stringClasses
	 * @param array $excludedClasses
	 * @return string
	 */
	public function drawClassesFromList($classes, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $addSpace = true, $addClassAttribute = false, $stringClasses = null, $excludedClasses = null) {
	    return $this->drawClassesFromMultipleArray(array($classes), $callerConfig, $addSpace, $addClassAttribute, $stringClasses, $excludedClasses);
	}
	
	/**
	 * @param string $string
	 * @param bool $addSpace
	 * @param bool $addClassAttribute
	 * @return string
	 */
	public function drawClassFromString($string, $addSpace = true, $addClassAttribute = false){
	    $result = $string;
	    if($addClassAttribute && !empty(trim($result))){
	        $result = 'class="'. $result .'"';
	    }
	    return $this->getStringLeftWithSpace($result, $addSpace);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param bool $addStyleAttribute
	 * @param bool $addSpace
	 * @param array $newStyleAttributes
	 * @param array $excludedStyleAttributes
	 * @return string
	 */
	public function drawAllStyleAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $addStyleAttribute = true, $addSpace = false, $newStyleAttributes = null, $excludedStyleAttributes = null) {
	    $allStyleAttributes = array();
	    $otherStyleAttributes = $this->getOtherStyleAttributes($globalConfig, $callerConfig);
	    $finalExcludedStyleAttributes = $excludedStyleAttributes;
	    if(is_array($otherStyleAttributes)){
	        $allStyleAttributes = array_merge($allStyleAttributes, $otherStyleAttributes);
	    }
	    if(is_array($newStyleAttributes)){
	        $allStyleAttributes = array_merge($allStyleAttributes, $newStyleAttributes);
	    }
	    if($callerConfig !== null){
	        $callerStyleAttributes = $callerConfig->getStyleAttributes();
	        if(is_array($callerStyleAttributes)){
	            $allStyleAttributes = array_merge($allStyleAttributes, $callerStyleAttributes);
	        }
	        $callerExcludedStyleAttributes = $callerConfig->getExcludedStyleAttributes();
	        if(is_array($callerExcludedStyleAttributes)){
	            $finalExcludedStyleAttributes = is_array($finalExcludedStyleAttributes) ? array_merge($finalExcludedStyleAttributes, $callerExcludedStyleAttributes) : $callerExcludedStyleAttributes;
	        }
	    }
	    $allStyleAttributes = array_merge($allStyleAttributes, $this->styleAttributes);
	    return $this->drawStyleAttributeFromList($allStyleAttributes, $addStyleAttribute, $addSpace, $finalExcludedStyleAttributes);
	}
	
	/**
	 * @param array $styleAttributes
	 * @param bool $addStyleAttribute
	 * @param bool $addSpace
	 * @param array $excludedStyleAttributes
	 * @return string
	 */
	public function drawStyleAttributeFromList($styleAttributes, $addStyleAttribute = false, $addSpace = false, $excludedStyleAttributes = null) {
	    $result = '';
	    if(!empty($styleAttributes)){
	        foreach ($styleAttributes as $key => $value) {
	            if(!is_array($excludedStyleAttributes) || !in_array($key, $excludedStyleAttributes)){
	                $result .= $key . ':' . $value . ';';
	            }
	        }
	    }
	    if(!empty($result) && $addStyleAttribute){
	        $result = $this->getStringLeftWithSpace('style="' . $result . '"', $addSpace);
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param bool $addStyleAttribute
	 * @param bool $addSpace
	 * @param array $excludedStyleAttributes
	 * @return string
	 */
	public function drawInitialStyleAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $addStyleAttribute = false, $addSpace = false, $excludedStyleAttributes = null) {
	    return $this->drawStyleAttributeFromList($this->styleAttributes, $addStyleAttribute, $addSpace, $excludedStyleAttributes);
	}
	
	public function convertToDatePicker($langIso = null, $format = null, $todayBtn = true, $clearBtn = true, $todayHighlight = true, $autoclose = true, $orientation = null) {
	    $groupName = 'datepicker'. $this->name . date('Y-m-d H:i:s');
	    $format = empty($format) ? 'yyyy-mm-dd' : $format;
	    $this->setJsInitializationRequired('datepicker', false, $groupName);
	    $this->addAttribute('data-locale', $langIso);
	    $this->addAttribute('data-format', $format);
	    $this->addAttribute('data-todayBtn', $todayBtn);
	    $this->addAttribute('data-clearBtn', $clearBtn);
	    $this->addAttribute('data-todayHighlight', $todayHighlight);
	    $this->addAttribute('data-autoclose', $autoclose);
	    if(!empty($orientation)){
	        $this->addAttribute('data-orientation', $orientation);
	    }
	}
	
	public function convertToDateTimePicker($langIso = null, $format = null, $todayBtn = true, $clearBtn = true, $todayHighlight = true, $autoclose = true, $orientation = null) {
	    $groupName = 'datetimepicker'. $this->name . date('Y-m-d H:i:s');
	    $format = empty($format) ? 'yyyy-mm-dd hh:ii' : $format;
	    $this->setJsInitializationRequired('datetimepicker', false, $groupName);
	    $this->addAttribute('data-locale', $langIso);
	    $this->addAttribute('data-format', $format);
	    $this->addAttribute('data-todayBtn', $todayBtn);
	    $this->addAttribute('data-clearBtn', $clearBtn);
	    $this->addAttribute('data-todayHighlight', $todayHighlight);
	    $this->addAttribute('data-autoclose', $autoclose);
	    if(!empty($orientation)){
	        $this->addAttribute('data-orientation', $orientation);
	    }
	}
	
	public function convertToRangeDatePicker($langIso = null, $format = null, $todayBtn = true, $clearBtn = true, $todayHighlight = true, $autoclose = true) {
	    $this->convertToDatePicker($langIso, $format, $todayBtn, $clearBtn, $todayHighlight, $autoclose);
	    $this->addClass('input-daterange');
	}
	
    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return string[]
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
     * @param boolean $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param boolean $onlyContentEnabled
     */
    public function setOnlyContentEnabled($onlyContentEnabled)
    {
        $this->onlyContentEnabled = $onlyContentEnabled;
    }
    
    /**
     * @return array
     */
    public function getStyleAttributes()
    {
        return $this->styleAttributes;
    }
}