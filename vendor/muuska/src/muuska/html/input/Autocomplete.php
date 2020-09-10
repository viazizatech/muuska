<?php
namespace muuska\html\input;

class Autocomplete extends AbstractHtmlInput{
    /**
     * @var string
     */
    protected $componentName = 'autocomplete';
    
    /**
     * @var string
     */
    protected $url;
    
    /**
     * @param string $url
     * @param string $name
     * @param mixed $value
     * @param string $placeholder
     */
    public function __construct($url, $name, $value = null, $placeholder = null) {
        parent::__construct('text', $name, $value, $placeholder);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::prepare()
	 */
	public function prepare(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if(!empty($this->name)){
	        $attributes['name'] = $this->name;
	    }
	    if($this->multiple){
	        $attributes['multiple'] = 'multiple';
	    }
	    return $attributes;
	}
	
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}