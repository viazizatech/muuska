<?php
namespace muuska\option\provider;

class StatusProvider extends AbstractOptionProvider
{
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::createTranslator()
     */
    protected function createTranslator(){
        return $this->getFrameworkTranslator('status');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::initOptions()
     */
    protected function initOptions()
	{
	    $this->addArrayOption(0, $this->l('Enabled'));
	    $this->addArrayOption(1, $this->l('Disabled'));
	}
}
