<?php
namespace muuska\controller\admin;

use muuska\constants\Names;
use muuska\controller\AbstractController;
use muuska\http\constants\RedirectionType;
use muuska\project\constants\ProjectType;
use muuska\project\setup\ProjectSetupManager;
use muuska\util\App;

class ModuleManagerAdminController extends AbstractController implements ProjectSetupManager
{
	/**
	 * @var \muuska\project\setup\ProjectSetup[]
	 */
	protected $projectSetups = array();
	
	/**
	 * @var \muuska\project\setup\ProjectManager
	 */
	protected $projectManager;
	
	protected function init()
    {
        parent::init();
        App::getApp()->initSetupManager($this, ProjectType::MODULE);
    }
    
    protected function processDefault(){
        $listPanel = App::htmls()->createListPanel($this->l('Installed modules'));
        $setups = $this->getInstalledSetups();
        $table = App::htmls()->createTable($setups);
        $configureUrlCreator = App::urls()->createDefaultObjectUrl(function ($data, $params = array(), $anchor = '', $mode = null) {
            return $this->urlCreator->createModuleUrl($data->getProject()->getName(), Names::HOME_CONTROLLER, null, $params, $anchor, $mode);
        });
        $table->createAction('configure', $configureUrlCreator, App::createHtmlString($this->l('Configure')));
        $this->createListAction($table, 'activate', $this->l('Activate'));
        $this->createListAction($table, 'deactivate', $this->l('Deactivate'));
        $this->createListAction($table, 'uninstall', $this->l('Uninstall'));
        $this->createListAction($table, 'reset', $this->l('Reset'));
        $itemCreator = App::htmls()->createDefaultListItemCreator(function($data, \muuska\html\listing\item\ListItemContainer $listItemContainer, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
            $project = $data->getProject();
            $item = $listItemContainer->defaultCreateItem($data, $globalConfig, $callerConfig);
            if($project->isActive()){
                $item->addDisabledAction('activate');
            }else{
                $item->addDisabledAction('deactivate');
            }
            if(!$data->isConfigurable()){
                $item->addDisabledAction('configure');
            }
            return $item;
        });
        $table->setItemCreator($itemCreator);
        
            
        $logoRenderer = App::renderers()->createDefaultValueRenderer(function ($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
            $logo = $data->getLogo($this->input->getLang(), $this->result->getAssetSetter());
            return ($logo !== null) ? $logo->generate($globalConfig, $callerConfig) : '';
        });

        $table->createField('logo', $logoRenderer, $this->l('Logo'));
        $nameRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data->getProject()->getName();
        }));
        $table->createField('name', $nameRenderer, $this->l('Name'));

        $displayNameRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data->getDisplayName($this->input->getLang());
        }));
        $table->createField('displayName', $displayNameRenderer, $this->l('Display name'));

        $descriptionRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data->getDescription($this->input->getLang());
        }));
        $table->createField('description', $descriptionRenderer, $this->l('Description'));
        $listPanel->setInnerContent($table);
        $this->result->setContent($listPanel);
    }
    
    protected function createListAction(\muuska\html\listing\AbstractList $list, $action, $label) {
        $initialParams = array('action' => $action);
        $intallUrlCreator = App::urls()->createDefaultObjectUrl(function($initialParams, $data, $params = array(), $anchor = '', $mode = null){
            $params['module_name'] = $data->getProject()->getName();
            return $this->urlCreator->createUrl($initialParams['action'], $params, $anchor, $mode);
        }, $initialParams);
        return $list->createAction($action, $intallUrlCreator, App::createHtmlString($label));
    }
    
    protected function processUninstall(){
        $moduleName = $this->input->getParam('module_name');
        if(empty($moduleName)){
            $this->result->addError($this->l('Module name is required'));
        }else{
            $setups = $this->getInstalledSetups($moduleName);
            if(isset($setups[0])){
                $uninstaller = $setups[0]->getUninstaller();
                $run = false;
                $operationOk = true;
                if($uninstaller !== null){
                    $uninstallerResult = $uninstaller->uninstall(App::utils()->createSetupInput($this->input, $this->urlCreator, $this->result->getAssetSetter()));
                    if(($uninstallerResult === null) || ($uninstallerResult->isOperationExecuted() && $uninstallerResult->isSuccessfullyExecuted())){
                        $run = true;
                    }else{
                        $this->result->addError($this->l('An error occurred while uninstalling the module'));
                    }
                }else{
                    $run = true;
                }
                if($run){
                    $this->projectManager->uninstall($setups[0]->getProject());
                }
                if($operationOk){
                    $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION, null, null, [], 'uninstall_ok'));
                }else{
                    $this->result->addError($this->l('An error occurred while uninstalling the module'));
                }
            }else{
                $this->result->addError($this->l('Module is not uninstallable'));
            }
        }
    }
    
    protected function processActivate(){
        $moduleName = $this->input->getParam('module_name');
        if(empty($moduleName)){
            $this->result->addError($this->l('Module name is required'));
        }else{
            $setups = $this->getInstalledSetups($moduleName);
            $module = $setups[0]->getProject();
            if(isset($setups[0]) && !$module->isActive()){
                $activator = $setups[0]->getActivator();
                $run = false;
                $operationOk = true;
                if($activator !== null){
                    $activatorResult = $activator->activate(App::utils()->createSetupInput($this->input, $this->urlCreator, $this->result->getAssetSetter()));
                    if(($activatorResult === null) || ($activatorResult->isOperationExecuted() && $activatorResult->isSuccessfullyExecuted())){
                        $run = true;
                    }else{
                        $this->result->addError($this->l('An error occurred while activating the module'));
                    }
                }else{
                    $run = true;
                }
                if($run){
                    $this->projectManager->activate($module);
                }
                if($operationOk){
                    $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION, null, null, [], 'activate_ok'));
                }else{
                    $this->result->addError($this->l('An error occurred while activating the module'));
                }
            }else{
                $this->result->addError($this->l('Module is not activable'));
            }
        }
    }
    
    protected function processDeactivate(){
        $moduleName = $this->input->getParam('module_name');
        if(empty($moduleName)){
            $this->result->addError($this->l('Module name is required'));
        }else{
            $setups = $this->getInstalledSetups($moduleName);
            $module = $setups[0]->getProject();
            if(isset($setups[0]) && $module->isActive()){
                $deactivator = $setups[0]->getDeactivator();
                $run = false;
                $operationOk = true;
                if($deactivator !== null){
                    $deactivatorResult = $deactivator->deactivate(App::utils()->createSetupInput($this->input, $this->urlCreator, $this->result->getAssetSetter()));
                    if(($deactivatorResult === null) || ($deactivatorResult->isOperationExecuted() && $deactivatorResult->isSuccessfullyExecuted())){
                        $run = true;
                    }else{
                        $this->result->addError($this->l('An error occurred while deactivating the module'));
                    }
                }else{
                    $run = true;
                }
                if($run){
                    $this->projectManager->deactivate($module);
                }
                if($operationOk){
                    $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION, null, null, [], 'deactivate_ok'));
                }else{
                    $this->result->addError($this->l('An error occurred while deactivating the module'));
                }
            }else{
                $this->result->addError($this->l('Module is not deactivable'));
            }
        }
    }
    
    protected function processReset(){
        $this->processUninstall();
        if($this->result->hasRedirection()){
            $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::OTHER_CONTROLLER, 'module-installer', 'install', array('module_name' => $this->input->getParam('module_name'))));
        }
    }
    
    /**
     * @param string $moduleName
     * @return \muuska\project\setup\ProjectSetup[]
     */
    protected function getInstalledSetups($moduleName = null){
        $result = array();
        foreach ($this->projectSetups as $setup) {
            $project = $setup->getProject();
            if($project->isInstalled() && ($project->getType() === ProjectType::MODULE)){
                if(!empty($moduleName)){
                    if($moduleName === $project->getName()){
                        $result[] = $setup;
                        break;
                    }
                }else{
                    $result[] = $setup;
                }
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetupManager::addSetup()
     */
    public function addSetup(\muuska\project\setup\ProjectSetup $setup)
    {
        $this->projectSetups[] = $setup;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectSetupManager::setProjectManager()
     */
    public function setProjectManager(\muuska\project\setup\ProjectManager $projectManager){
        $this->projectManager = $projectManager;
    }
}