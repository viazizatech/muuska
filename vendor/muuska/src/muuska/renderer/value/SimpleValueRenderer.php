<?php
namespace muuska\renderer\value;
class SimpleValueRenderer implements ValueRenderer{
    /**
     * @var \muuska\getter\Getter
     */
    protected $valueGetter;
    
    /**
     * @param \muuska\getter\Getter $valueGetter
     */
    public function __construct(\muuska\getter\Getter $valueGetter){
        $this->valueGetter = $valueGetter;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\value\ValueRenderer::renderValue()
     */
    public function renderValue($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        return $this->valueGetter->get($data);
    }
}