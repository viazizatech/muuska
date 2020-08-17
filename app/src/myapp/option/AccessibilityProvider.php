<?php
namespace myapp\option;

use muuska\option\provider\AbstractOptionProvider;
use muuska\util\App;
use myapp\constants\Accessibility;

class AccessibilityProvider extends AbstractOptionProvider
{
    protected function createTranslator(){
        return $this->getProjectTranslator(App::getApp(), 'accessibility');
    }
    
    protected function initOptions()
    {
        $this->addArrayOption(Accessibility::PUBLIC, $this->l('Public'));
        $this->addArrayOption(Accessibility::PRIVATE, $this->l('Private'));
    }
}
