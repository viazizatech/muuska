<?php
namespace muuska\project;

use muuska\constants\Names;
use muuska\controller\event\ControllerPageFormatingListener;
use muuska\project\constants\SubAppName;

class AdminFramework extends AbstractSubProject implements ControllerPageFormatingListener
{
    /**
     * @param \muuska\project\Framework $framework
     */
    public function __construct(\muuska\project\Framework $framework){
        parent::__construct(SubAppName::BACK_OFFICE, $framework);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\AbstractSubProject::createController()
     */
    public function createController(\muuska\controller\ControllerInput $input) {
        $result = null;
        if($input->checkName(Names::HOME_CONTROLLER)){
            $result = new \muuska\controller\admin\HomeAdminController($input);
        }elseif($input->checkName(Names::LOGIN_CONTROLLER)){
            $result = new \muuska\controller\admin\LoginAdminController($input);
        }elseif($input->checkName('module-installer')){
            $result = new \muuska\controller\admin\ModuleInstallerAdminController($input);
        }elseif($input->checkName('module-manager')){
            $result = new \muuska\controller\admin\ModuleManagerAdminController($input);
        }elseif($input->checkName('resource')){
            $result = new \muuska\controller\admin\ResourceAdminController($input);
        }elseif($input->checkName('administrator')){
            $result = new \muuska\controller\admin\AdministratorAdminController($input);
        }elseif($input->checkName('profile')){
            $result = new \muuska\controller\admin\ProfileAdminController($input);
        }elseif($input->checkName('super-administrator')){
            $result = new \muuska\controller\admin\SuperAdministratorAdminController($input);
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\event\ControllerPageFormatingListener::onAppControllerPageFormating()
     */
    public function onAppControllerPageFormating(\muuska\controller\event\ControllerPageFormatingEvent $event){
        $event->addMainNavItem(array('title' => 'test'));
    }
}
