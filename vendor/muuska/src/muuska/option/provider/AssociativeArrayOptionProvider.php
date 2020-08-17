<?php
namespace muuska\option\provider;

class AssociativeArrayOptionProvider extends AbstractOptionProvider{
    
    /**
     * @param array $array
     */
    public function __construct($array){
        parent::__construct();
        $this->addOptionsFromAssociativeArray($array);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::initOptions()
     */
    protected function initOptions()
    {
        
    }
}