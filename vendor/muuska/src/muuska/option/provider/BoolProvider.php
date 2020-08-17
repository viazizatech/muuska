<?php
namespace muuska\option\provider;

class BoolProvider extends AbstractOptionProvider
{
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::createTranslator()
     */
    protected function createTranslator(){
        return $this->getFrameworkTranslator('bool');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::initOptions()
     */
    protected function initOptions()
	{
	    $this->addArrayOption(0, $this->l('No'));
	    $this->addArrayOption(1, $this->l('Yes'));
	}
}
