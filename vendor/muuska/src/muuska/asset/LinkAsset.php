<?php
namespace muuska\asset;

use muuska\asset\constants\AssetSubType;
use muuska\asset\constants\AssetType;

class LinkAsset extends AttributeSingleAsset
{
    /**
     * @param array $attributes
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct($attributes, $priority = null, $locationInPage = null)
    {
        parent::__construct(AssetType::LINK, AssetSubType::CUSTOM, $priority, $locationInPage);
        $this->setAttributes($attributes);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        return '<link'.$this->drawAttributes().'>';
    }
}
