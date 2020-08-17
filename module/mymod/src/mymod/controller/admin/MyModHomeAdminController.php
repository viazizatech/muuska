<?php
namespace mymod\controller\admin;

use muuska\controller\AbstractController;
use muuska\util\App;
use muuska\html\constants\AlertType;

class MyModHomeAdminController extends AbstractController
{
    protected function processDefault()
    {
        $this->result->setContent(App::createHtmlString('My mod admin home'));
    }
    
    protected function processTestAlert()
    {
        $subProject = $this->input->getSubProject();
        $errorConfig = App::translations()->createAlertTranslationConfig(AlertType::DANGER);
        $errorTranslator = $subProject->getTranslator($errorConfig);
        $this->result->addError($errorTranslator->translate($this->input->getLang(), 'Name is required'));
        
        $successConfig = App::translations()->createAlertTranslationConfig(AlertType::SUCCESS);
        $successTranslator = $subProject->getTranslator($successConfig);
        $this->result->addSuccess($successTranslator->translate($this->input->getLang(), 'Name is valid'));
    }
    
    protected function processTestController()
    {
        $this->result->setContent(App::createHtmlString($this->l('My mod home page')));
    }
}
