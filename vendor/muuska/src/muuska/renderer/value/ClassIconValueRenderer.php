<?php
namespace muuska\renderer\value;

use muuska\util\App;

class ClassIconValueRenderer implements ValueRenderer{
    /**
     * @var \muuska\getter\Getter
     */
    protected $finalClassGetter;
    
    /**
     * @var string
     */
    protected $prependFA;
    
    /**
     * @param \muuska\getter\Getter $finalClassGetter
     * @param bool $prependFA
     */
    public function __construct(\muuska\getter\Getter $finalClassGetter = null, $prependFA = true) {
        $this->finalClassGetter = $finalClassGetter;
        $this->prependFA = $prependFA;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\value\ValueRenderer::renderValue()
     */
    public function renderValue($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null)
    {
        return App::htmls()->createClassIcon(($this->prependFA ? 'fa fa-' : '') . $this->getFinalClass($data));
    }
    
    /**
     * @param mixed $data
     * @return mixed
     */
    public function getFinalClass($data) {
        return (($data !== null) && ($this->finalClassGetter !== null)) ? $this->finalClassGetter->get($data) : $data;
    }
}