<?php
namespace muuska\option\provider;
use muuska\constants\Gender;

class GenderProvider extends AbstractOptionProvider
{
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::createTranslator()
     */
    protected function createTranslator(){
        return $this->getFrameworkTranslator('gender');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::initOptions()
     */
    protected function initOptions()
    {
        $this->addArrayOption(Gender::MALE, $this->l('Male'));
        $this->addArrayOption(Gender::FEMALE, $this->l('Female'));
    }
}
