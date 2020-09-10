<?php
namespace muuska\project;

use muuska\project\constants\SubAppName;
use muuska\constants\Names;

class FrontFramework extends AbstractSubProject
{
    /**
     * @param \muuska\project\Framework $framework
     */
    public function __construct(\muuska\project\Framework $framework){
        parent::__construct(SubAppName::FRONT_OFFICE, $framework);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\AbstractSubProject::createController()
     */
    public function createController(\muuska\controller\ControllerInput $input) {
        $result = null;
        if($input->checkName('install')){
            $result = new \muuska\controller\front\InstallController($input);
        }elseif($input->checkName(Names::HOME_CONTROLLER)){
            $result = new \muuska\controller\front\HomeController($input);
        }
        return $result;
    }
}
