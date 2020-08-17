<?php
namespace muuska\asset;

use muuska\asset\constants\AssetSubType;
use muuska\asset\constants\AssetType;

class CssContent extends AttributeSingleAsset
{
    /**
     * @var string
     */
    protected $content;
    
    /**
     * @param string $content
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct($content, $priority = null, $locationInPage = null)
    {
        parent::__construct(AssetType::CSS, AssetSubType::CONTENT, $priority, $locationInPage);
        $this->setContent($content);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        return $this->getFinalStringFromContent($this->content, $outputConfig);
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
}
