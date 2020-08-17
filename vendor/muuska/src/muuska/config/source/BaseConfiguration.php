<?php
namespace muuska\config\source;

use muuska\config\AbstractConfiguration;

class BaseConfiguration extends AbstractConfiguration{
    
    /**
     * @param array $array
     */
    public function __construct($array = array()){
        $this->configValues = $array;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\AbstractConfiguration::load()
     */
    protected function load()
    {
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::save()
     */
    public function save()
    {
        return true;
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