<?php
namespace muuska\renderer\template\source;

use muuska\renderer\template\Template;
use muuska\util\App;

class PHPTemplate extends Template{
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\HtmlContentRenderer::renderHtml()
     */
    public function renderHtml(\muuska\html\HtmlContent $htmlContent, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null)
    {
        $templateTranslator = $this->getFinalTranslator();
        if($templateTranslator === null){
            $templateTranslator = App::translations()->createSameTranslator();
        }
        return $this->getPHPTemplateContent($this->getFullLocation(), $htmlContent, $globalConfig, $callerConfig, App::translations()->createDefaultLangTranslator($templateTranslator, $globalConfig->getLang()));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\template\Template::getFileSuffix()
     */
    public function getFileSuffix()
    {
        return '.php';
    }
    
    /**
     * @param string $file
     * @param \muuska\html\HtmlContent $item
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param \muuska\translation\LangTranslator $translator
     * @return string
     */
    public function getPHPTemplateContent($file, \muuska\html\HtmlContent $item, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, \muuska\translation\LangTranslator $translator = null) {
        ob_start();
        require $file;
        return ob_get_clean();
    }
}