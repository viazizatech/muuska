<?php
namespace muuska\project;

use muuska\config\Configuration;

interface SubApplication extends SubProject
{
    /**
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     * @param bool $outputEnabled
     * @return \muuska\controller\ControllerResult
     */
    public function runController(\muuska\dao\DAOFactory $daoFactory, \muuska\http\event\RequestParsingEvent $requestEvent, $outputEnabled = true);
    
    /**
     * @return string
     */
    public function getConfiguredThemeName();
    
    /**
     * @return \muuska\util\theme\Theme
     */
    public function getActiveTheme();
    
    /**
     * @param string $name
     * @return \muuska\util\theme\Theme
     */
    public function getThemeByName($name);
    
    /**
     * @return Configuration
     */
    public function getConfig();
}
