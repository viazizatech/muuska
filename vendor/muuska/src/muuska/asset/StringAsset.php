<?php
namespace muuska\asset;

use muuska\asset\constants\AssetSubType;
use muuska\asset\constants\AssetType;

class StringAsset extends SingleAsset
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
        parent::__construct(AssetType::STRING_CONTENT, AssetSubType::CONTENT, $priority, $locationInPage);
        $this->setContent($content);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        return $this->content;
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
