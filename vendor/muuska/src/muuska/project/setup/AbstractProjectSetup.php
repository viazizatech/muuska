<?php
namespace muuska\project\setup;

use muuska\util\App;
use muuska\constants\ActionCode;

abstract class AbstractProjectSetup implements ProjectSetup
{
    /**
     * @var \muuska\project\Project
     */
    protected $project;
    
    /**
     * @var bool
     */
    protected $customPresentationEnabled;
    
    public function __construct(\muuska\project\ProjectInput $projectInput){
        
    }
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getProject()
     */
    public function getProject(){
        return $this->project;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getInstaller()
     */
    public function getInstaller(){
        return App::projects()->createDefaultProjectInstaller($this->getProject(), App::daos()->createProjectDAOInstallInput($this->getProject(), $this->getModelDefinitions()));    
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getUninstaller()
     */
    public function getUninstaller(){
        return App::projects()->createDefaultProjectUninstaller($this->getProject(), App::daos()->createProjectDAOUninstallInput($this->getProject(), $this->getModelDefinitions()));  
    }
    
    /**
     * @return \muuska\model\ModelDefinition[]
     */
    protected function getModelDefinitions(){
        return array();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getActivator()
     */
    public function getActivator(){
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getDeactivator()
     */
    public function getDeactivator(){
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getDisplayName()
     */
    public function getDisplayName($lang){
        return $this->translate($lang, 'displayName', 'project_display_name');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getDescription()
     */
    public function getDescription($lang){
        return $this->translate($lang, 'description', 'project_description');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getLogo()
     */
    public function getLogo($lang, \muuska\asset\AssetSetter $assetSetter){
        return $this->project->createHtmlImage('logo.png', 'Logo', 'Logo');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::isCustomPresentationEnabled()
     */
    public function isCustomPresentationEnabled(){
        return $this->customPresentationEnabled;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getCustomPresentation()
     */
    public function getCustomPresentation($lang, \muuska\asset\AssetSetter $assetSetter){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getEvents()
     */
    public function getEvents()
    {
        return array();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::getResources()
     */
    public function getResources()
    {
        return array();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetup::isConfigurable()
     */
    public function isConfigurable(){
        return true;
    }
    
    /**
     * @param string $lang
     * @param string $string
     * @param string $context
     * @return string
     */
    protected function translate($lang, $string, $context = null){
        $result = $string;
        $tranlator = $this->project->getTranslator(App::translations()->createMainTranslationConfig());
        if($tranlator !== null){
            $result = $tranlator->translate($lang, $string, $context);
        }
        return $result;
    }
    
    protected function createCrudResources($name, $addStatus = false, $addState = false){
        $result = array('code' => $name, 'subs' => array(ActionCode::DEFAULT_PROCESS, ActionCode::VIEW, ActionCode::ADD, ActionCode::UPDATE, ActionCode::DELETE));
        if($addStatus){
            $result['subs'][] = ActionCode::ACTIVATE;
            $result['subs'][] = ActionCode::DEACTIVATE;
        }
        if($addState){
            $result['subs'][] = ActionCode::UPDATE_SATE;
        }
        return $result;
    }
}
