<?php
namespace muuska\html\input;

class Textarea extends AbstractHtmlInput{
	/**
	 * @var string
	 */
	protected $componentName = 'textarea';
	
	/**
	 * @var int
	 */
	protected $rows;
	
	/**
	 * @var int
	 */
	protected $cols;
	
	/**
	 * @param string $name
	 * @param string $value
	 * @param int $rows
	 * @param int $cols
	 */
	public function __construct($name, $value = null, $rows = null, $cols = null) {
	    parent::__construct($name, $value);
		$this->setRows($rows);
		$this->setCols($cols);
		
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
	    if(!empty($this->rows)){
	        $attributes['rows'] = $this->rows;
	    }
	    if(!empty($this->cols)){
	        $attributes['cols'] = $this->cols;
	    }
	    return $attributes;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('textarea', $globalConfig, $callerConfig) . $this->getValue().$this->drawEndTag('textarea', $globalConfig, $callerConfig);
	}

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @param int $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    /**
     * @param int $cols
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
    }
}