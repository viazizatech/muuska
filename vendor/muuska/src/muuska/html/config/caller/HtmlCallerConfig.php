<?php
namespace muuska\html\config\caller;
use muuska\util\ExtraDataProvider;

interface HtmlCallerConfig extends ExtraDataProvider{
    /**
     * @param string $class
     * @return bool
     */
    public function hasClass($class);
    
    /**
     * @return \muuska\renderer\HtmlContentRenderer
     */
    public function getRenderer();
    
    /**
     * @return bool
     */
    public function hasRenderer();

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getCallerInstance();

    /**
     * @return bool
     */
    public function isVisible();

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @return string[]
     */
    public function getClasses();

    /**
     * @return bool
     */
    public function isOnlyContentEnabled();
    
    /**
     * @param string $name
     * @return bool
     */
    public function isAttributeExcluded($name);

    /**
     * @return array
     */
    public function getStyleAttributes();

    /**
     * @return string[]
     */
    public function getExcludedAttributes();

    /**
     * @return string[]
     */
    public function getExcludedStyleAttributes();

    /**
     * @return string[]
     */
    public function getExcludedClasses();

    /**
     * @return bool
     */
    public function isVisibilityChanged();
    
    /**
     * @param string $areaName
     * @return bool
     */
    public function isAreaDisabled($areaName);
    
    /**
     * @return bool
     */
    public function hasPreferredTag();
    
    /**
     * @return string
     */
    public function getPreferredTag();
}