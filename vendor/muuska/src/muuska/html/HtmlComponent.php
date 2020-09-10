<?php
namespace muuska\html;
use muuska\util\App;
use muuska\util\AbstractExtraDataProvider;
class HtmlComponent extends AbstractExtraDataProvider implements HtmlContent{
	/**
	 * @var string
	 */
	protected $componentName;
	
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var \muuska\renderer\HtmlContentRenderer
	 */
	protected $renderer;
	
	/**
	 * @var string[]
	 */
	protected $disabledAreas = array();
	
	/**
	 * @param string $name
	 * @param string $componentName
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 */
	public function __construct($name = null, $componentName = null, \muuska\renderer\HtmlContentRenderer $renderer = null) {
	    $this->setName($name);
	    $this->setRenderer($renderer);
	    if(!empty($componentName)){
	        $this->componentName = $componentName;
	    }
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlContent::generate()
	 */
	public function generate(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    $content = '';
	    $this->prepare($globalConfig, $callerConfig);
	    if(($callerConfig !== null) && $callerConfig->hasRenderer()){
	        $content = $callerConfig->getRenderer()->renderHtml($this, $globalConfig, $callerConfig);
	    }elseif ($this->hasRenderer()){
	        $content = $this->renderer->renderHtml($this, $globalConfig, $callerConfig);
	    }else{
	        $content = $this->renderDefault($globalConfig, $callerConfig);
	    }
	    return $content;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string
	 */
	public function renderDefault(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    $content = '';
	    if(!empty($this->componentName)){
	        $themeRenderer = $globalConfig->hasTheme() ? $globalConfig->getTheme()->getHtmlRenderer($this->componentName) : null;
	        $content = ($themeRenderer !== null) ? $themeRenderer->renderHtml($this, $globalConfig, $callerConfig) : $this->renderStatic($globalConfig, $callerConfig);
	    }else{
	        $content = $this->renderStatic($globalConfig, $callerConfig);
	    }
	    return $content;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return '';
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 */
	public function prepare(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\ContentCreator::createContent()
	 */
	public function createContent()
	{
	    return $this;
	}
	
	/**
	 * @return \muuska\renderer\HtmlContentRenderer
	 */
	public function getRenderer() {
	    return $this->renderer;
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 */
	public function setRendererFromFunction($callback, $initialParams = null){
	    $this->setRenderer($this->createRendererFromFunction($callback, $initialParams));
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\renderer\DefaultHtmlContentRenderer
	 */
	public function createRendererFromFunction($callback, $initialParams = null){
	    return App::renderers()->createDefaultHtmlContentRenderer($callback, $initialParams);
	}
	
	/**
	 * @return string
	 */
	public function getComponentName() {
		return $this->componentName;
	}
	
	/**
	 * @return bool
	 */
	public function hasRenderer() {
		return ($this->renderer !== null);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\ContentCreator::getName()
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @return bool
	 */
	public function hasName() {
		return !empty($this->name);
	}
	
	/**
	 * @param string $name
	 */
	public function setName($name){
		$this->name = $name;
	}
	
	/**
	 * @param string $areaName
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return bool
	 */
	public function isAreaDisabled($areaName, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    return (in_array($areaName, $this->disabledAreas) || (($callerConfig !== null) && $callerConfig->isAreaDisabled($areaName)));
	}
	
	/**
	 * @param string $stringClasses
	 * @param array $styleAttributes
	 * @param array $attributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return \muuska\html\config\caller\DefaultHtmlCallerConfig
	 */
	public function createCallerConfig($stringClasses = null, $styleAttributes = null, $attributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null) {
	    return $this->htmls()->createDefaultHtmlCallerConfig($this, $stringClasses, $styleAttributes, $attributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param HtmlContent[] $array
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $areaName
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderContentList($array, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $areaName, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
        $result = '';
        if(!$this->isAreaDisabled($areaName, $callerConfig) && !empty($array)){
            $result .= $prefix;
            foreach ($array as $item) {
                if($item !== null){
                    $result .= $item->generate($globalConfig, $currentCallerConfig);
                }
            }
            $result .= $suffix;
        }
        return $result;
    }
    
    /**
     * @param array $associativeArray
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $areaName
     * @param string $prefix
     * @param string $suffix
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function renderAssociativeContentList($associativeArray, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $areaName, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
        $result = '';
        if(!$this->isAreaDisabled($areaName, $callerConfig) && !empty($associativeArray)){
            foreach ($associativeArray as $tmpKey => $def) {
                $tmpPrefix = isset($def['prefix']) ? $def['prefix'] : '';
                $tmpSuffix = isset($def['suffix']) ? $def['suffix'] : '';
                $tmpContent = isset($def['content']) ? $def['content'] : null;
                $tmpCurrentCallerConfig = isset($def['currentCallerConfig']) ? $def['currentCallerConfig'] : null;
                $result .= $this->renderContent($tmpContent, $globalConfig, $callerConfig, $tmpKey, $tmpPrefix, $tmpSuffix, $tmpCurrentCallerConfig);
            }
            if(!empty($result)){
                $result = $prefix . $result .$suffix;
            }
        }
        return $result;
    }
    
    /**
     * @param HtmlContent $content
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $areaName
     * @param string $prefix
     * @param string $suffix
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function renderContent(?HtmlContent $content, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $areaName, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
        $result = '';
        if(!$this->isAreaDisabled($areaName, $callerConfig) && ($content !== null)){
            $result = $prefix.$content->generate($globalConfig, $currentCallerConfig).$suffix;
        }
        return $result;
    }
    
    /**
     * @param string $string
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $areaName
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function renderString($string, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $areaName, $prefix = '', $suffix = ''){
	    $result = '';
	    if(!$this->isAreaDisabled($areaName, $callerConfig) && !empty($string)){
            $result = $prefix.$string.$suffix;
        }
        return $result;
    }
    
    /**
     * @param string $string
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function drawString($string, $prefix = '', $suffix = ''){
        return !empty($string) ? $prefix.$string.$suffix : '';
    }
	
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param string $path
     * @return \muuska\renderer\template\Template
     */
    public function createThemeTemplate(\muuska\html\config\HtmlGlobalConfig $globalConfig, $path)
    {
        $template = $globalConfig->hasTheme() ? $globalConfig->getTheme()->createTemplate($path) : null;
        return $template;
    }
    
    /**
     * @param \muuska\renderer\HtmlContentRenderer $renderer
     */
    public function setRenderer(?\muuska\renderer\HtmlContentRenderer $renderer)
    {
        $this->renderer = $renderer;
    }
    
    /**
     * @param string[] $disabledAreas
     */
    public function setDisabledAreas($disabledAreas)
    {
        $this->disabledAreas = array();
        $this->addDisabledAreas($disabledAreas);
    }
    
    /**
     * @param string $area
     */
    public function addDisabledArea($area) {
        $this->disabledAreas[] = $area;
    }
    
    /**
     * @param string[] $disabledAreas
     */
    public function addDisabledAreas($disabledAreas) {
        if (is_array($disabledAreas)) {
            foreach ($disabledAreas as $area) {
                $this->addDisabledArea($area);
            }
        }
    }
    
    /**
     * @return string[]
     */
    public function getDisabledAreas()
    {
        return $this->disabledAreas;
    }
	
    /**
     * @return \muuska\instantiator\Htmls
     */
    public function htmls()
    {
		return App::htmls();
    }
}