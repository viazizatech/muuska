<?php
namespace muuska\html\input;

class FileUpload extends AbstractHtmlInput{
    /**
     * @var string
     */
    protected $componentName = 'file_upload';
    
	/**
	 * @var bool
	 */
	protected $multiple;
	
	/**
	 * @var string
	 */
	protected $accept;
	
	/**
	 * @var string
	 */
	protected $uploadUrl;
	
	/**
	 * @var \muuska\html\HtmlContent[]
	 */
	protected $previews = array();
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $previewTemplate;
	
	/**
	 * @var bool
	 */
	protected $detailsSavingEnabled;
	
	/**
	 * @var string
	 */
	protected $deleteUrl;
	
	/**
	 * @var array
	 */
	protected $allowedExtensions;
	
	/**
	 * @var array
	 */
	protected $excludedExtensions;
	
	/**
	 * @param string $uploadUrl
	 * @param boolean $multiple
	 * @param string $accept
	 */
	public function __construct($uploadUrl, $multiple = false, $accept = '') {
		$this->setUploadUrl($uploadUrl);
		$this->setMultiple($multiple);
		$this->setAccept($accept);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if(!empty($this->uploadUrl)){
	        $attributes['data-url'] = $this->uploadUrl;
	    }
	    if(!empty($this->accept)){
	        $attributes['data-accept'] = $this->accept;
	    }
	    if($this->deleteUrl){
	        $attributes['data-delete_url'] = $this->deleteUrl;
	    }
	    if($this->detailsSavingEnabled){
	        $attributes['data-details_saving_enabled'] = $this->detailsSavingEnabled;
	    }
	    if(!empty($this->excludedExtensions)){
	        $attributes['data-excluded_extensions'] = implode(',', $this->excludedExtensions);
	    }
	    if(!empty($this->allowedExtensions)){
	        $attributes['data-allowed_extensions'] = implode(',', $this->allowedExtensions);
	    }
	    return $attributes;
	}
	
	/**
	 * @return bool
	 */
	public function hasPreviews(){
	    return !empty($this->previews);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderPreviews(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContentList($this->previews, $globalConfig, $callerConfig, 'previews', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderPreviewTemplate(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->previewTemplate, $globalConfig, $callerConfig, 'previewTemplate', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param string $trueValue
	 * @param string $falseValue
	 * @param boolean $addSpace
	 * @return string
	 */
	public function drawHasPreviews($trueValue, $falseValue = '', $addSpace = false){
	    return $this->getStringFromCondition($this->hasPreviews(), $trueValue, $falseValue, $addSpace);
	}
	
	/**
	 * @return bool
	 */
	public function hasPreviewTemplate(){
	    return ($this->previewTemplate !== null);
	}
	
	/**
	 * @return bool
	 */
	public function hasAccept(){
	    return !empty($this->accept);
	}
	
    /**
     * @return boolean
     */
    public function isMultiple()
    {
        return $this->multiple;
    }

    /**
     * @return string
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * @return string
     */
    public function getUploadUrl()
    {
        return $this->uploadUrl;
    }

    /**
     * @return \muuska\html\HtmlContent[]
     */
    public function getPreviews()
    {
        return $this->previews;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getPreviewTemplate()
    {
        return $this->previewTemplate;
    }

    /**
     * @return boolean
     */
    public function isDetailsSavingEnabled()
    {
        return $this->detailsSavingEnabled;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->deleteUrl;
    }

    /**
     * @return array
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * @return array
     */
    public function getExcludedExtensions()
    {
        return $this->excludedExtensions;
    }

    /**
     * @param boolean $multiple
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
    }

    /**
     * @param string $accept
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
    }

    /**
     * @param string $uploadUrl
     */
    public function setUploadUrl($uploadUrl)
    {
        $this->uploadUrl = $uploadUrl;
    }

    /**
     * @param \muuska\html\HtmlContent[] $previews
     */
    public function setPreviews($previews)
    {
        $this->previews = array();
        $this->addPreviews($previews);
    }
    
    /**
     * @param \muuska\html\HtmlContent[] $previews
     */
    public function addPreviews($previews)
    {
        if (is_array($previews)) {
            foreach ($previews as $preview) {
                $this->addPreview($preview);
            }
        }
    }
    
    /**
     * @param \muuska\html\HtmlContent $preview
     * @return \muuska\html\HtmlContent
     */
    public function addPreview(\muuska\html\HtmlContent $preview){
        return $this->previews[] = $preview;
    }

    /**
     * @param \muuska\html\HtmlContent $previewTemplate
     */
    public function setPreviewTemplate(?\muuska\html\HtmlContent $previewTemplate)
    {
        $this->previewTemplate = $previewTemplate;
    }

    /**
     * @param boolean $detailsSavingEnabled
     */
    public function setDetailsSavingEnabled($detailsSavingEnabled)
    {
        $this->detailsSavingEnabled = $detailsSavingEnabled;
    }

    /**
     * @param string $deleteUrl
     */
    public function setDeleteUrl($deleteUrl)
    {
        $this->deleteUrl = $deleteUrl;
    }

    /**
     * @param string[] $allowedExtensions
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * @param string[] $excludedExtensions
     */
    public function setExcludedExtensions($excludedExtensions)
    {
        $this->excludedExtensions = $excludedExtensions;
    }
}