<?php
namespace muuska\option\provider;

class KeyValueOptionProvider extends AbstractOptionProvider{
    /**
     * @param array $array
     */
    public function __construct($array){
        parent::__construct();
        $this->addOptionsFromKeyValueArray($array);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::initOptions()
     */
    protected function initOptions()
    {
        
    }
}