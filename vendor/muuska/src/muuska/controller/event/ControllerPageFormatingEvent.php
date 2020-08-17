<?php
namespace muuska\controller\event;

use muuska\asset\constants\AssetNames;
use muuska\util\App;
use muuska\constants\Names;
use muuska\project\constants\ProjectType;

class ControllerPageFormatingEvent extends ControllerEvent
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
     * @var \muuska\html\listing\tree\MenuList
     */
    protected $defaultMainNav;
    
    /**
     * @param \muuska\controller\Controller $source
     * @param \muuska\html\HtmlPage $htmlPage
     * @param \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\url\ControllerUrlCreator $urlCreator
     * @param \muuska\controller\ControllerResult $controllerResult
     * @param \muuska\controller\param\ControllerParamResolver $paramResolver
     * @param array $params
     */
    public function __construct(\muuska\controller\Controller $source, \muuska\html\HtmlPage $htmlPage, \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor, \muuska\controller\ControllerInput $controllerInput, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\controller\ControllerResult $controllerResult, \muuska\controller\param\ControllerParamResolver $paramResolver, $params = array()){
        parent::__construct($source, $controllerInput, $urlCreator, $controllerResult, $paramResolver, $params);
		$this->htmlPage = $htmlPage;
		$this->areaCreatorEditor = $areaCreatorEditor;
		$this->defaultMainNav = App::htmls()->createMenuList();
		$this->defaultMainNav->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createArrayValueGetter('title')));
		$this->defaultMainNav->createIconField(App::renderers()->createClassIconValueRenderer(App::getters()->createArrayValueGetter('icon')), false);
		$this->setMainNav($this->defaultMainNav);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\event\ControllerEvent::getFinalEventCode()
	 */
	public function getFinalEventCode($code)
	{
	    return parent::getFinalEventCode('page_formating');
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
	    App::getTools()->autoFormatHtmlPage($this->htmlPage, $this->areaCreatorEditor, $theme, $mainConfig, 'controller_template', $defaultTemplateFile);
	}
	
	/**
	 * @param boolean $addSubAppGroup
	 */
	public function autoAddAssets($addSubAppGroup = true, $addProjectAssets = true)
	{
	    $this->addThemeAssets($addSubAppGroup);
	    $this->addFrameworkAssets($addSubAppGroup);
	    $this->addAppCustomAssets($addSubAppGroup);
	    if($addProjectAssets){
	        $this->addProjectAssets($addSubAppGroup);
	    }
	}
	
	/**
	 * @param boolean $addSubAppGroup
	 */
	public function addFrameworkAssets($addSubAppGroup = true)
	{
	    App::getFramework()->createAssetGroup(AssetNames::FRAMEWORK_DEFAULT_GROUP, $this->getAssetSetter());
	    if($addSubAppGroup){
	        $subFramework = App::getFramework()->getSubProject($this->getSubAppName());
	        if($subFramework !== null){
	            $subFramework->createAssetGroup(AssetNames::SUB_FRAMEWORK_DEFAULT_GROUP, $this->getAssetSetter());
	        }
	    }
	}
	
	/**
	 * @param boolean $addSubAppGroup
	 */
	public function addThemeAssets($addSubAppGroup = true)
	{
	    $theme = $this->getTheme();
	    if($theme !== null){
	        $theme->createAssetGroup(AssetNames::THEME_DEFAULT_GROUP, $this->getAssetSetter());
	        if($addSubAppGroup){
	            $theme->createAssetGroup($this->getSubAppName().'_'.AssetNames::THEME_DEFAULT_GROUP, $this->getAssetSetter());
	        }
	    }
	}
	
	/**
	 * @param boolean $addSubAppGroup
	 */
	public function addAppCustomAssets($addSubAppGroup = true)
	{
	    App::getApp()->createAssetGroup(AssetNames::APP_CUSTOM_GROUP, $this->getAssetSetter());
	    if($addSubAppGroup){
	        $this->getSubApplication()->createAssetGroup(AssetNames::SUB_APP_CUSTOM_GROUP, $this->getAssetSetter());
	    }
	}
	
	/**
	 * @param boolean $addSubAppGroup
	 */
	public function addProjectAssets($addSubAppGroup = true)
	{
	    $this->getProject()->createAssetGroup(AssetNames::PROJECT_DEFAULT_GROUP, $this->getAssetSetter());
	    if($addSubAppGroup){
	        $this->getSubProject()->createAssetGroup(AssetNames::SUB_PROJECT_DEFAULT_GROUP, $this->getAssetSetter());
	    }
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
     * @return \muuska\asset\AssetSetter
     */
    public function getAssetSetter()
    {
        return $this->controllerResult->getAssetSetter();
    }
    
	/**
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 */
	public function setPageRenderer(?\muuska\renderer\HtmlContentRenderer $renderer)
    {
        $this->htmlPage->setRenderer($renderer);
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
    
    public function addMainNavItem($itemData){
        $navData = $this->defaultMainNav->getData();
        $navData[] = $itemData;
        $this->defaultMainNav->setData($navData);
    }
    
    /**
     * @param array $data
     * @return \muuska\html\listing\Masonry
     */
    public function createInnerNav($data)
    {
        $innerNav = App::htmls()->createMenuList();
        $innerNav->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createArrayValueGetter('title')));
        $innerNav->createIconField(App::renderers()->createClassIconValueRenderer(App::getters()->createArrayValueGetter('icon')), false);
        $this->addHtmlComponent($innerNav, Names::INNER_NAV);
        return $innerNav;
    }
    
    /**
     * @param string $moduleName
     * @param array $data
     * @return \muuska\html\listing\Masonry
     */
    public function createModuleInnerNav($moduleName, $data)
    {
        foreach ($data as $key => $row) {
            if(!isset($row['projectType'])){
                $data[$key]['projectType'] = ProjectType::MODULE;
                $data[$key]['projectName'] = $moduleName;
            }
        }
        return $this->createInnerNav($data);
    }
    
    /**
     * @return \muuska\html\listing\tree\MenuList
     */
    public function getDefaultMainNav()
    {
        return $this->defaultMainNav;
    }
}
