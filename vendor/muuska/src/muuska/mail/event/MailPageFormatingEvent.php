<?php
namespace muuska\mail\event;

use muuska\asset\constants\AssetNames;
use muuska\util\App;
use muuska\util\event\EventObject;
use muuska\constants\Names;

class MailPageFormatingEvent extends EventObject
{
    /**
     * @var \muuska\html\HtmlPage
     */
    protected $htmlPage;
    
    /**
     * @var \muuska\html\areacreator\AreaCreatorEditor
     */
    protected $areaCreatorEditor;
    
    /**
     * @var \muuska\html\config\HtmlGlobalConfig
     */
    protected $htmlGlobalConfig;
    
    /**
     * @param object $source
     * @param \muuska\html\HtmlPage $htmlPage
     * @param \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor
     * @param \muuska\html\config\HtmlGlobalConfig $htmlGlobalConfig
     * @param array $params
     */
    public function __construct(object $source, \muuska\html\HtmlPage $htmlPage, \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor, \muuska\html\config\HtmlGlobalConfig $htmlGlobalConfig, $params = array()){
        parent::__construct($source, $params);
		$this->htmlPage = $htmlPage;
		$this->areaCreatorEditor = $areaCreatorEditor;
		$this->htmlGlobalConfig = $htmlGlobalConfig;
	}
	
	/**
	 * @return string
	 */
	public function getFinalEventCode() {
	    return 'mail_page_formating';
	}
	
	/**
	 * @param \muuska\util\theme\Theme $theme
	 * @param \muuska\config\Configuration $mainConfig
	 * @param string $defaultTemplateFile
	 * @param boolean $autoAsset
	 */
	public function formatFromTheme(\muuska\util\theme\Theme $theme, \muuska\config\Configuration $mainConfig = null, $defaultTemplateFile = '', $autoAsset = true)
	{
	    if($autoAsset){
	        $this->autoAddAssets($theme);
	    }
	    $this->autoFormatPage($theme, $mainConfig, $defaultTemplateFile);
	}
	
	/**
	 * @param \muuska\util\theme\Theme $theme
	 * @param \muuska\config\Configuration $mainConfig
	 * @param string $defaultTemplateFile
	 */
	public function autoFormatPage(\muuska\util\theme\Theme $theme, \muuska\config\Configuration $mainConfig = null, $defaultTemplateFile = '')
	{
	    App::getTools()->autoFormatHtmlPage($this->htmlPage, $this->areaCreatorEditor, $theme, $mainConfig, 'mail_template', $defaultTemplateFile);
	}
	
	/**
	 * @param \muuska\util\theme\Theme $theme
	 */
	public function autoAddAssets(\muuska\util\theme\Theme $theme)
	{
	    $this->addThemeAssets($theme);
	    $this->addFrameworkAssets();
	    $this->addAppCustomAssets();
	}
	
	public function addFrameworkAssets()
	{
	    App::getFramework()->createAssetGroup(AssetNames::FRAMEWORK_MAIL_DEFAULT_GROUP, $this->getAssetSetter());
	}
	
	/**
	 * @param \muuska\util\theme\Theme $theme
	 */
	public function addThemeAssets(\muuska\util\theme\Theme $theme)
	{
	    $theme->createAssetGroup(AssetNames::THEME_MAIL_DEFAULT_GROUP, $this->getAssetSetter());
	}
	
	public function addAppCustomAssets()
	{
	    App::getApp()->createAssetGroup(AssetNames::APP_MAIL_CUSTOM_GROUP, $this->getAssetSetter());
	}
    
    /**
     * @return \muuska\html\HtmlPage
     */
    public function getHtmlPage()
    {
        return $this->htmlPage;
    }
    
    /**
     * @return \muuska\html\areacreator\AreaCreatorEditor
     */
    public function getAreaCreatorEditor()
    {
        return $this->areaCreatorEditor;
    }
    
    /**
     * @return bool
     */
    public function hasTheme()
    {
        return $this->htmlGlobalConfig->hasTheme();
    }
    
    /**
     * @return \muuska\util\theme\Theme
     */
    public function getTheme()
    {
        return $this->htmlGlobalConfig->getTheme();
    }
    
    /**
     * @return \muuska\html\config\HtmlGlobalConfig
     */
    public function getHtmlGlobalConfig()
    {
        return $this->htmlGlobalConfig;
    }
    
    /**
     * @return \muuska\asset\AssetSetter
     */
    public function getAssetSetter()
    {
        return $this->htmlGlobalConfig->getAssetSetter();
    }
    
    /**
     * @param \muuska\html\ContentCreator $contentCreator
     * @param string $defaultPosition
     */
    public function addContentCreator(\muuska\html\ContentCreator $contentCreator, $defaultPosition = null){
        $this->areaCreatorEditor->addContentCreator($contentCreator, $defaultPosition);
    }
    
    /**
     * @param \muuska\html\HtmlComponent $htmlContent
     * @param string $name
     * @param string $defaultPosition
     */
    public function addHtmlComponent(\muuska\html\HtmlComponent $htmlContent, $name = null, $defaultPosition = null){
        if(!empty($name)){
            $htmlContent->setName($name);
        }
        $this->addContentCreator($htmlContent, $defaultPosition);
    }
    
    /**
     * @param \muuska\html\HtmlComponent $mainNav
     */
    public function setMainNav(\muuska\html\HtmlComponent $mainNav){
        $this->addHtmlComponent($mainNav, Names::MAIN_NAV);
    }
    
    /**
     * @param \muuska\html\ContentCreator[] $contentCreators
     */
    public function addContentCreators($contentCreators){
        $this->areaCreatorEditor->addContentCreators($contentCreators);
    }
    
    /**
     * @param \muuska\html\ContentCreator[] $contentCreators
     */
    public function setContentCreators($contentCreators){
        $this->areaCreatorEditor->setContentCreators($contentCreators);
    }
    
    /**
     * @param string $position
     * @param string $contentName
     */
    public function addContentAtPosition($position, $contentName){
        $this->areaCreatorEditor->addContentAtPosition($position, $contentName);
    }
    
    /**
     * @param string $position
     * @param array $contentNames
     */
    public function addContentsAtPosition($position, $contentNames){
        $this->areaCreatorEditor->addContentAtPosition($position, $contentNames);
    }
    
    /**
     * @param string $position
     * @param array $contentNames
     */
    public function setContentsAtPosition($position, $contentNames){
        $this->areaCreatorEditor->setContentsAtPosition($position, $contentNames);
    }
    
    /**
     * @param array $contentPositions
     */
    public function setContentPositions($contentPositions){
        $this->areaCreatorEditor->setContentPositions($contentPositions);
    }
    
    /**
     * @param array $contentPositions
     */
    public function addContentPositions($contentPositions){
        $this->areaCreatorEditor->addContentPositions($contentPositions);
    }
}
