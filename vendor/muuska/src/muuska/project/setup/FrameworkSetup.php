<?php
namespace muuska\project\setup;

use muuska\util\App;

class FrameworkSetup extends AbstractProjectSetup
{
    public function __construct(\muuska\project\Framework $framework){
        $this->project = $framework;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\AbstractProjectSetup::getModelDefinitions()
     */
    protected function getModelDefinitions(){
        return array(App::configs()->getConfigModelDefinitionInstance(), App::securities()->getAuthentificationDefinitionInstance(), App::securities()->getGroupDefinitionInstance(), App::securities()->getResourceDefinitionInstance(), App::securities()->getAuthentificationGroupDefinitionInstance(), App::securities()->getAuthentificationAccessDefinitionInstance(), App::securities()->getGroupAccessDefinitionInstance());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\AbstractProjectSetup::getEvents()
     */
    public function getEvents() {
        return array('admin_controller_page_formating');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\AbstractProjectSetup::getResources()
     */
    public function getResources()
    {
        return array();
    }
}
