<?php
namespace myapp;

use muuska\project\AbstractSubApplication;

class FrontSubApplication extends AbstractSubApplication
{
    public function createController(\muuska\controller\ControllerInput $input) {
        $result = null;
        if ($input->checkName('hello-world')) {
            $result = new \myapp\controller\front\HelloWorldController($input);
        }elseif ($input->checkName('association')) {
            $result = new \myapp\controller\front\AssoModelController ($input);
        }elseif ($input->checkName('home')) {
            $result = new \myapp\controller\front\AssoModelController ($input);
         } elseif ($input->checkName('test-model')) {
            $result = new \myapp\controller\front\TestAssoDaoController($input);
        }elseif ($input->checkName('test-param')) {
            $result = new \myapp\controller\front\TestParamController($input);
        }elseif ($input->checkName('test-dao')) {
            $result = new \myapp\controller\front\TestDAOController($input);
        }elseif ($input->checkName('test-translation')) {
            $result = new \myapp\controller\front\TestTranslationController($input);
        }elseif ($input->checkName('test-html')) {
            $result = new \myapp\controller\front\TestHtmlController($input);
        }
        return $result;
    }
}
 