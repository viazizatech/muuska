<?php
namespace muuska\html\input;

abstract class Option extends AbstractHtmlInput{
	/**
	 * @var \muuska\option\provider\OptionProvider
	 */
	protected $optionProvider;
	
	/**
	 * @param string $name
	 * @param \muuska\option\provider\OptionProvider $optionProvider
	 * @param mixed $value
	 */
	public function __construct($name, \muuska\option\provider\OptionProvider $optionProvider = null, $value = null) {
		parent::__construct($name, $value);
		$this->setOptionProvider($optionProvider);
	}
	
	/**
	 * @return bool
	 */
	public function hasOptionProvider(){
	    return ($this->optionProvider !== null);
	}
	
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getOptionId($key){
	    $id = str_replace(' ', '', $this->name .'_'. $key);
	    $id = str_replace(array('[', ']'), '_', $id);
	    return $id;
	}
	
	/**
	 * @param \muuska\option\Option $option
	 * @return boolean
	 */
	public function isOptionSelected(\muuska\option\Option $option){
	    $result = (($this->value !== null) && ($option->getValue() == $this->value));
	    return $result;
	} 
	
    /**
     * @return \muuska\option\provider\OptionProvider
     */
    public function getOptionProvider()
    {
        return $this->optionProvider;
    }

    /**
     * @param \muuska\option\provider\OptionProvider $optionProvider
     */
    public function setOptionProvider(?\muuska\option\provider\OptionProvider $optionProvider)
    {
        $this->optionProvider = $optionProvider;
    }
    
    /**
     * @return \muuska\option\Option[]
     */
    public function getOptions() {
        return $this->hasOptionProvider() ? $this->optionProvider->getOptions() : array();
    }
}