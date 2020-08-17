<?php
namespace muuska\html\dropdown;
class SplitDropdown extends Dropdown{
    /**
     * @var string
     */
    protected $componentName = 'split_dropdown';
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $defaultContent;
    
    /**
     * @param \muuska\html\HtmlContent $defaultContent
     */
    public function __construct(\muuska\html\HtmlContent $defaultContent = null) {
        $this->setDefaultContent($defaultContent);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $prefix
     * @param string $suffix
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function renderDefaultContent(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
        return $this->renderContent($this->defaultContent, $globalConfig, $callerConfig, 'defaultContent', $prefix, $suffix);
    }
    
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getDefaultContent()
    {
        return $this->defaultContent;
    }

    /**
     * @param \muuska\html\HtmlContent $defaultContent
     */
    public function setDefaultContent(?\muuska\html\HtmlContent $defaultContent)
    {
        $this->defaultContent = $defaultContent;
    }
}