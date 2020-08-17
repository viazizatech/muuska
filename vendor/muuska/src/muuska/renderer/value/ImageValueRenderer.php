<?php
namespace muuska\renderer\value;

use muuska\util\App;

class ImageValueRenderer implements ValueRenderer{
    /**
     * @var \muuska\getter\Getter
     */
    protected $finalSrcGetter;
    
    /**
     * @var string
     */
    protected $altText;
    
    /**
     * @param string $altText
     * @param \muuska\getter\Getter $finalUrlGetter
     */
    public function __construct($altText = '', \muuska\getter\Getter $finalSrcGetter = null) {
        $this->$altText = $altText;
        $this->finalSrcGetter = $finalSrcGetter;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\value\ValueRenderer::renderValue()
     */
    public function renderValue($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null)
    {
        $src = $this->getFinalSrc($data);
        return App::htmls()->createHtmlImage($src, $this->altText);
    }
    
    /**
     * @param mixed $data
     * @return mixed
     */
    public function getFinalSrc($data) {
        return (($data !== null) && ($this->finalSrcGetter !== null)) ? $this->finalSrcGetter->get($data) : $data;
    }
}