<?php
namespace myapp\controller\front;

use muuska\controller\AbstractController;
use muuska\util\App;
use myapp\model\AssociationDefinition;

class TestParamController extends AbstractController
{
    protected function initParamResolver()
    {
        /*$parsers = array(App::controllers()->createDefaultControllerParamParser('name', true));
        $this->paramResolver = App::controllers()->createDefaultControllerParamResolver($this->input, $this->result, $parsers);*/
        
        $parsers = array(App::controllers()->createModelControllerParamParser(AssociationDefinition::getInstance(), 'id', true));
        $this->paramResolver = App::controllers()->createDefaultControllerParamResolver($this->input, $this->result, $parsers);
    }
    
    protected function processDefault()
    {
        $param = $this->paramResolver->getParam('id');
        $library = $param->getObject();
        var_dump($library);
    }
}
