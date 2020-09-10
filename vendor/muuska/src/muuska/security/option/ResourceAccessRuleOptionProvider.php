<?php
namespace muuska\security\option;

use muuska\option\provider\AbstractOptionProvider;
use muuska\security\constants\ResourceAccessRule;

class ResourceAccessRuleOptionProvider extends AbstractOptionProvider{
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::createTranslator()
     */
    protected function createTranslator(){
        return $this->getFrameworkTranslator('resource_access_rule');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::initOptions()
     */
    protected function initOptions()
    {
        $this->addArrayOption(ResourceAccessRule::AUTHORIZATION, $this->l('Authorization'));
        $this->addArrayOption(ResourceAccessRule::SUPER_USER, $this->l('Super user'));
        $this->addArrayOption(ResourceAccessRule::NONE, $this->l('None'));
    }
}