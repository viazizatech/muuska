<?php
namespace muuska\renderer;

class StaticRenderer implements HtmlContentRenderer
{
    /**
     * @var string
     */
    protected $content;
    
    /**
     * @param string $content
     */
    public function __construct($content){
        $this->content = $content;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\HtmlContentRenderer::renderHtml()
     */
    public function renderHtml(\muuska\html\HtmlContent $htmlContent, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        return $this->content;
    }
}
