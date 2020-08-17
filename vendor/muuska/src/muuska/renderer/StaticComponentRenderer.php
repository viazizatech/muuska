<?php
namespace muuska\renderer;

class StaticComponentRenderer implements HtmlContentRenderer
{
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\HtmlContentRenderer::renderHtml()
     */
    public function renderHtml(\muuska\html\HtmlContent $htmlContent, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        return $htmlContent->renderStatic($globalConfig, $callerConfig);
    }
}
