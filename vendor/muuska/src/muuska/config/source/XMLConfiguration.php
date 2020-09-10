<?php
namespace muuska\config\source;

use muuska\util\App;

class XMLConfiguration extends FileConfiguration{
    /**
     * {@inheritDoc}
     * @see \muuska\config\AbstractConfiguration::load()
     */
    protected function load()
    {
        $this->configValues = App::getFileTools()->getArrayFromXML($this->fileName);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::save()
     */
    public function save()
    {
        return App::getFileTools()->saveXMLContentFromArray($this->fileName, $this->configValues);
    }
}