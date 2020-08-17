<?php
namespace muuska\html;
class HtmlCustomElement extends HtmlElement{
    /**
     * @var \muuska\html\areacreator\AreaCreator
     */
    protected $areaCreator;
    
    /**
     * @param \muuska\html\areacreator\AreaCreator $areaCreator
     * @param \muuska\renderer\HtmlContentRenderer $renderer
     * @param string $name
     */
    public function __construct(\muuska\html\areacreator\AreaCreator $areaCreator = null, \muuska\renderer\HtmlContentRenderer $renderer = null, $name = null){
        if($areaCreator !== null){
            $this->setAreaCreator($areaCreator);
        }
        if($renderer !== null){
            $this->setRenderer($renderer);
        }
        $this->setName($name);
    }
    
    /**
     * @return bool
     */
    public function hasAreaCreator(){
        return ($this->areaCreator !== null);
    }
    
    /**
     * @param string $position
     * @return \muuska\html\HtmlContent[]
     */
    public function getContentsByPosition($position){
        return $this->hasAreaCreator() ? $this->areaCreator->createContentsByPosition($position) : array();
    }
    
    /**
     * @param string $position
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $prefix
     * @param string $suffix
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function drawContentsByPosition($position, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
        return $this->renderContentList($this->getContentsByPosition($position), $globalConfig, $callerConfig, $position, $prefix, $suffix, $currentCallerConfig);
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasContent($name){
        return $this->hasAreaCreator() ? $this->areaCreator->hasContentCreator($name) : false;
    }
    
    /**
     * @param string $name
     * @return \muuska\html\HtmlContent
     */
    public function getContentByName($name){
        return $this->hasContent($name) ? $this->areaCreator->createContentByName($name) : null;
    }
    
    /**
     * @param string $name
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $prefix
     * @param string $suffix
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function drawContentByName($name, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig , $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
        return $this->renderContent($this->getContentByName($name), $globalConfig, $callerConfig, $name, $prefix, $suffix, $currentCallerConfig);
    }
    
    /**
     * @return \muuska\html\areacreator\AreaCreator
     */
    public function getAreaCreator()
    {
        return $this->areaCreator;
    }

    /**
     * @param \muuska\html\areacreator\AreaCreator $areaCreator
     */
    public function setAreaCreator(?\muuska\html\areacreator\AreaCreator $areaCreator)
    {
        $this->areaCreator = $areaCreator;
    }
}