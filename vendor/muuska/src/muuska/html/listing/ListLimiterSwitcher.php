<?php
namespace muuska\html\listing;
use muuska\html\HtmlElement;
class ListLimiterSwitcher extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'limiter_switcher';
    
	/**
	 * @var \muuska\option\provider\OptionProvider
	 */
	protected $optionProvider;
	
	/**
	 * @var mixed
	 */
	protected $selectedValue;
	
	/**
	 * @var bool
	 */
	protected $editable;
	
	/**
	 * @var \muuska\url\pagination\ListLimiterUrl
	 */
	protected $urlCreator;
	
	/**
	 * @var string[]
	 */
	protected $linkClasses = array();
	
	/**
	 * @param \muuska\option\provider\OptionProvider $optionProvider
	 * @param mixed $selectedValue
	 * @param \muuska\url\pagination\ListLimiterUrl $urlCreator
	 */
	public function __construct(\muuska\option\provider\OptionProvider $optionProvider = null, $selectedValue = null, \muuska\url\pagination\ListLimiterUrl $urlCreator = null) {
		$this->setOptionProvider($optionProvider);
		$this->setSelectedValue($selectedValue);
		$this->setUrlCreator($urlCreator);
	}
	
	/**
	 * @param \muuska\option\Option $option
	 * @return string
	 */
	public function getOptionUrl(\muuska\option\Option $option) {
	    return $this->getLimiterUrl($option->getValue());
	}
	
	/**
	 * @param mixed $value
	 * @return string
	 */
	public function getLimiterUrl($value) {
	    return $this->hasUrlCreator() ? $this->urlCreator->createListLimiterUrl($value) : '#';
	}
	
	/**
	 * @return boolean
	 */
	public function hasUrlCreator() {
	    return ($this->urlCreator !== null);
	}
	
	/**
	 * @param string $stringClasses
	 * @param boolean $addSpace
	 * @param boolean $addClassAttribute
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function drawLinkClasses($stringClasses = null, $addSpace = true, $addClassAttribute = false, $excludedClasses = null) {
	    return $this->drawClassesFromList($this->linkClasses, null, $addSpace, $addClassAttribute, $stringClasses, $excludedClasses);
	}
	
	/**
	 * @return boolean
	 */
	public function hasOptionProvider(){
	    return ($this->optionProvider !== null);
	}
	
	/**
	 * @param \muuska\option\Option $option
	 * @return boolean
	 */
	public function isOptionSelected(\muuska\option\Option $option){
	    $result = (($this->selectedValue !== null) && ($option->getValue() == $this->selectedValue));
	    return $result;
	}
	
	/**
	 * @return string
	 */
	public function getSelectedLabel(){
	    return $this->hasOptionProvider() ? $this->optionProvider->getLabelFromValue($this->selectedValue) : '';
	}
	
    /**
     * @return \muuska\option\provider\OptionProvider
     */
    public function getOptionProvider()
    {
        return $this->optionProvider;
    }

    /**
     * @return mixed
     */
    public function getSelectedValue()
    {
        return $this->selectedValue;
    }

    /**
     * @return boolean
     */
    public function isEditable()
    {
        return $this->editable;
    }

    /**
     * @return \muuska\url\pagination\ListLimiterUrl
     */
    public function getUrlCreator()
    {
        return $this->urlCreator;
    }

    /**
     * @param \muuska\option\provider\OptionProvider $optionProvider
     */
    public function setOptionProvider(?\muuska\option\provider\OptionProvider $optionProvider)
    {
        $this->optionProvider = $optionProvider;
    }

    /**
     * @param mixed $selectedValue
     */
    public function setSelectedValue($selectedValue)
    {
        $this->selectedValue = $selectedValue;
    }

    /**
     * @param boolean $editable
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;
    }

    /**
     * @param \muuska\url\pagination\ListLimiterUrl $urlCreator
     */
    public function setUrlCreator(?\muuska\url\pagination\ListLimiterUrl $urlCreator)
    {
        $this->urlCreator = $urlCreator;
    }
    
    /**
     * @return \muuska\option\Option[]
     */
    public function getOptions() {
        return $this->hasOptionProvider() ? $this->optionProvider->getOptions() : array();
    }
}