<?php
namespace muuska\project\setup;

interface ProjectSetup
{
    /**
     * @return array
     */
    public function getEvents();
    
    /**
     * @return array
     */
    public function getResources();
    
    /**
     * @return \muuska\project\Project
     */
    public function getProject();
    
    /**
     * @return \muuska\util\setup\Installer
     */
    public function getInstaller();
    
    /**
     * @return \muuska\util\setup\Uninstaller
     */
    public function getUninstaller();
    
    /**
     * @return \muuska\util\setup\Activator
     */
    public function getActivator();
    
    /**
     * @return \muuska\util\setup\Deactivator
     */
    public function getDeactivator();
    
    /**
     * @param string $lang
     * @return string
     */
    public function getDisplayName($lang);
    
    /**
     * @param string $lang
     * @return string
     */
    public function getDescription($lang);
    
    /**
     * @param string $lang
     * @param \muuska\asset\AssetSetter $assetSetter
     * @return \muuska\html\HtmlContent
     */
    public function getLogo($lang, \muuska\asset\AssetSetter $assetSetter);
    
    /**
     * @return bool
     */
    public function isCustomPresentationEnabled();
    
    /**
     * @param string $lang
     * @param \muuska\asset\AssetSetter $assetSetter
     * @return \muuska\html\HtmlContent
     */
    public function getCustomPresentation($lang, \muuska\asset\AssetSetter $assetSetter);
    
    /**
     * @return bool
     */
    public function isConfigurable();
}
