<?php
namespace muuska\html\input;

use muuska\html\HtmlElement;

class UploadPreview extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'upload_preview';
    
	/**
	 * @var mixed
	 */
	protected $value;
	
	/**
	 * @var string
	 */
	protected $filePreview;
	
	/**
	 * @var bool
	 */
	protected $fileSaved;
	
	/**
	 * @var bool
	 */
	protected $useAsTemplate;
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param string $filePreview
	 * @param boolean $fileSaved
	 * @param boolean $useAsTemplate
	 */
	public function __construct($name, $value, $filePreview, $fileSaved = false, $useAsTemplate = false) {
		$this->setName($name);
		$this->setValue($value);
		$this->setFilePreview($filePreview);
		$this->setFileSaved($fileSaved);
		$this->setUseAsTemplate($useAsTemplate);
	}
	
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getFilePreview()
    {
        return $this->filePreview;
    }

    /**
     * @return boolean
     */
    public function isFileSaved()
    {
        return $this->fileSaved;
    }

    /**
     * @return boolean
     */
    public function isUseAsTemplate()
    {
        return $this->useAsTemplate;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $filePreview
     */
    public function setFilePreview($filePreview)
    {
        $this->filePreview = $filePreview;
    }

    /**
     * @param boolean $fileSaved
     */
    public function setFileSaved($fileSaved)
    {
        $this->fileSaved = $fileSaved;
    }

    /**
     * @param boolean $useAsTemplate
     */
    public function setUseAsTemplate($useAsTemplate)
    {
        $this->useAsTemplate = $useAsTemplate;
    }
}