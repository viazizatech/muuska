<?php
namespace muuska\option\provider;
use muuska\project\constants\SubAppName;

class SubAppNameProvider extends AbstractOptionProvider{
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::createTranslator()
     */
    protected function createTranslator(){
        return $this->getFrameworkTranslator('sub_app_name');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::initOptions()
     */
    protected function initOptions()
    {
        $this->addArrayOption(SubAppName::FRONT_OFFICE, $this->l('Front office'));
        $this->addArrayOption(SubAppName::BACK_OFFICE, $this->l('Back office'));
        $this->addArrayOption(SubAppName::CUSTOM, $this->l('Custom'));
        $this->addArrayOption(SubAppName::API, $this->l('API'));
    }
}