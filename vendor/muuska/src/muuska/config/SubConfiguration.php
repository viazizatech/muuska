<?php
namespace muuska\config;

class SubConfiguration extends AbstractConfiguration{
    
    /**
     * @var \muuska\config\Configuration
     */
    protected $parentConfig;
    
    /**
     * @var string
     */
    protected $keyInParent;
    
    /**
     * @param \muuska\config\Configuration $parentConfig
     * @param string $keyInParent
     * @param array $array
     */
    public function __construct(\muuska\config\Configuration $parentConfig, $keyInParent, $array = array()){
        $this->parentConfig = $parentConfig;
        $this->keyInParent = $keyInParent;
        $this->configValues = $array;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\AbstractConfiguration::load()
     */
    protected function load()
    {
        
    }
    
    protected function onChange(){
        $this->parentConfig->setArray($this->keyInParent, $this->configValues);
        parent::onChange();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::save()
     */
    public function save()
    {
        return $this->parentConfig->save();
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