<?php
namespace muuska\config\source;

use muuska\util\App;

class JSONConfiguration extends FileConfiguration{
    /**
     * {@inheritDoc}
     * @see \muuska\config\AbstractConfiguration::load()
     */
    protected function load()
    {
        $this->configValues = App::getFileTools()->getArrayFromJsonFile($this->fileName);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::save()
     */
    public function save()
    {
        return $this->saveContent(json_encode($this->configValues, JSON_PRETTY_PRINT));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\AbstractConfiguration::serializeArray()
     */
    protected function serializeArray($values)
    {
        return $values;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\AbstractConfiguration::unserializeArray()
     */
    protected function unserializeArray($content)
    {
        return $content;
    }
}