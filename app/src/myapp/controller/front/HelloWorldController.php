<?php
namespace myapp\controller\front;

use muuska\controller\AbstractController;
use muuska\util\App;
use muuska\html\constants\AlertType;

class HelloWorldController extends AbstractController
{
    protected function processDefault()
    {
        $this->result->addError('Erreur de test');
    }
    
    protected function processSayGoodbye()
    {
        $this->result->setContent(App::createHtmlString('Good bye!'));
    }
    
    protected function processTestDefaultParam()
    {
        
    }
}
