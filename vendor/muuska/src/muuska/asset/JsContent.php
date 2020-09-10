<?php
namespace muuska\asset;

use muuska\asset\constants\AssetSubType;
use muuska\asset\constants\AssetType;

class JsContent extends AttributeSingleAsset
{
    /**
     * @var string
     */
    protected $content;
    
    /**
     * @var bool
     */
    protected $jqueryWrapperEnabled;
    
    /**
     * @param string $content
     * @param boolean $jqueryWrapperEnabled
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct($content, $jqueryWrapperEnabled = false, $priority = null, $locationInPage = null)
    {
        parent::__construct(AssetType::JS, AssetSubType::CONTENT, $priority, $locationInPage);
        $this->setContent($content);
        $this->setJqueryWrapperEnabled($jqueryWrapperEnabled);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        $content = ($this->jqueryWrapperEnabled ? '$(document).ready(function(){' : '') . $this->content.($this->jqueryWrapperEnabled ? '});' : '');
        return $this->getFinalStringFromContent($content, $outputConfig);
    }
    
    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
    /**
     * @return boolean
     */
    public function isJqueryWrapperEnabled()
    {
        return $this->jqueryWrapperEnabled;
    }

    /**
     * @param boolean $jqueryWrapperEnabled
     */
    public function setJqueryWrapperEnabled($jqueryWrapperEnabled)
    {
        $this->jqueryWrapperEnabled = $jqueryWrapperEnabled;
    }
}
