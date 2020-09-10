<?php
namespace muuska\renderer;
use muuska\util\FunctionCallback;

class DefaultHtmlContentRenderer extends FunctionCallback implements HtmlContentRenderer{
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\HtmlContentRenderer::renderHtml()
     */
    public function renderHtml(\muuska\html\HtmlContent $htmlContent, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null)
    {
        $result = null;
        if($this->callback !== null){
            if(empty($this->initialParams)){
                $result = call_user_func($this->callback, $htmlContent, $globalConfig, $callerConfig);
            }else{
                $result = call_user_func($this->callback, $this->initialParams, $htmlContent, $globalConfig, $callerConfig);
            }
        }
        return $result;
    }
}