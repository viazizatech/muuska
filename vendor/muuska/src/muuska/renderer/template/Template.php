<?php
namespace muuska\renderer\template;

use muuska\renderer\HtmlContentRenderer;

abstract class Template implements HtmlContentRenderer{
    /**
     * @var string
     */
    protected $relativeFile;
    
    /**
     * @var string
     */
    protected $basePath;
    
    /**
     * @var \muuska\translation\TemplateTranslator
     */
    protected $baseTranslator;
    
    /**
     * @var string
     */
    protected $innerPath;
    
    /**
     * @var \muuska\translation\TemplateTranslator
     */
    protected $innerTranslator;
	
    /**
     * @param string $relativeFile
     * @param string $basePath
     * @param \muuska\translation\TemplateTranslator $baseTranslator
     * @param string $innerPath
     * @param \muuska\translation\TemplateTranslator $innerTranslator
     */
    public function __construct($relativeFile, $basePath, \muuska\translation\TemplateTranslator $baseTranslator = null, $innerPath = null, \muuska\translation\TemplateTranslator $innerTranslator = null){
        $this->relativeFile = $relativeFile;
        $this->basePath = $basePath;
        $this->baseTranslator = $baseTranslator;
        $this->innerPath = $innerPath;
        $this->innerTranslator = $innerTranslator;
    }
	
    /**
     * @param string $relativeFile
     * @param boolean $innerEnabled
     * @return \muuska\renderer\template\Template
     */
    public function createNewInstanceFromCurrent($relativeFile, $innerEnabled = true){
        $innerPath = $innerEnabled ? $this->innerPath : null;
        $innerTranslator = $innerEnabled ? $this->innerTranslator : null;
        return new static($relativeFile, $this->basePath, $this->baseTranslator, $innerPath, $innerTranslator);
    }
    
    /**
     * @param string $relativeFile
     * @param \muuska\html\HtmlContent $item
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @return string
     */
    public function renderTpl($relativeFile, \muuska\html\HtmlContent $item, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig){
        return $this->createNewInstanceFromCurrent($relativeFile, true)->renderHtml($item, $globalConfig, $callerConfig);
    }
    
    /**
     * @param string $relativeFile
     * @param \muuska\html\HtmlContent $item
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @return string
     */
    public function renderBaseTpl($relativeFile, \muuska\html\HtmlContent $item, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig){
        return $this->createNewInstanceFromCurrent($relativeFile, false)->renderHtml($item, $globalConfig, $callerConfig);
    }
	
	/**
	 * @return boolean
	 */
	public function exist(){
	    return $this->fileExist($this->relativeFile);
	}
	
	/**
	 * @param string $file
	 * @return boolean
	 */
	public function fileExist($file){
	    return file_exists($this->getFileFullLocation($file));
	}
	
	/**
	 * @return string
	 */
	public function getFullLocation(){
	    return $this->getFileFullLocation($this->relativeFile);
	}
	
	/**
	 * @param string $file
	 * @return string
	 */
	public function getFileFullLocation($file){
	    return $this->basePath.$this->innerPath.$file.$this->getFileSuffix();
	}
	
	/**
	 * @return string
	 */
	public abstract function getFileSuffix();
	
	/**
	 * @return \muuska\translation\TemplateTranslator
	 */
	public function getFinalTranslator() {
	    $result = null;
	    if (empty($this->innerPath)) {
	        $result = ($this->baseTranslator !== null) ? $this->baseTranslator->getNewTranslator($this->relativeFile) : null;
	    }else{
	        $result = ($this->innerTranslator !== null) ? $this->innerTranslator->getNewTranslator($this->relativeFile) : null;
	        if(($result === null) && ($this->baseTranslator !== null)){
	            $result = $this->baseTranslator->getNewTranslator($this->innerPath.$this->relativeFile);
	        }
	    }
	    return $result;
	}
}