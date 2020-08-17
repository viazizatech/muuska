<?php
namespace muuska\renderer\value;
class OptionLabelRenderer implements ValueRenderer{
    /**
     * @var \muuska\option\provider\OptionProvider
     */
    protected $optionProvider;
    
    /**
     * @var \muuska\getter\Getter
     */
    protected $finalValueGetter;
    
    /**
     * @param \muuska\option\provider\OptionProvider $optionProvider
     * @param \muuska\getter\Getter $finalValueGetter
     */
    public function __construct(\muuska\option\provider\OptionProvider $optionProvider, \muuska\getter\Getter $finalValueGetter = null) {
        $this->optionProvider = $optionProvider;
        $this->finalValueGetter = $finalValueGetter;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\value\ValueRenderer::renderValue()
     */
    public function renderValue($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null)
    {
        $value = $this->getFinalValue($data);
        return ($value !== null) ? $this->optionProvider->getLabelFromValue($value) : '';
    }
    
    /**
     * @param mixed $data
     * @return mixed
     */
    public function getFinalValue($data) {
        return (($data !== null) && ($this->finalValueGetter !== null)) ? $this->finalValueGetter->get($data) : $data;
    }
}