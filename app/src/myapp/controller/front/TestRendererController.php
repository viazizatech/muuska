<?php
namespace myapp\controller\front;

use muuska\controller\AbstractController;
use muuska\util\App;
use muuska\html\constants\AlertType;

class TestRendererController extends AbstractController
{
    protected function processDefault()
    {
        $this->result->addAlerts(AlertType::INFO, array('Info 1', 'Info 2'));
    }
    
    protected function processSayGoodbye()
    {
        $this->result->setContent(App::createHtmlString('Good bye!'));
    }
    
    protected function processTestDefaultParam()
    {
        
    }
}
