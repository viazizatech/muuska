<?php
namespace muuska\controller\admin;

use muuska\controller\AbstractController;
use muuska\http\constants\RedirectionType;
use muuska\project\constants\ProjectType;
use muuska\project\setup\ProjectSetupManager;
use muuska\util\App;
use muuska\asset\constants\AssetOutputMode;

class ModuleInstallerAdminController extends AbstractController implements ProjectSetupManager
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
        $listPanel = App::htmls()->createListPanel($this->l('Available modules'));
        $setups = $this->getUninstalledSetups();
        $table = App::htmls()->createTable($setups);
        $intallUrlCreator = App::urls()->createDefaultObjectUrl(function($data, $params = array(), $anchor = '', $mode = null){
            $params['module_name'] = $data->getProject()->getName();
            return $this->urlCreator->createUrl('install', $params, $anchor, $mode);
        });
        $table->createAction('install', $intallUrlCreator, App::createHtmlString($this->l('Install')));
        
        $logoRenderer = App::renderers()->createDefaultValueRenderer(function ($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
            $newGlobalConfig = App::htmls()->createDefaultHtmlGlobalConfig($globalConfig->getLang(), $globalConfig->getAssetSetter(), $globalConfig->getTheme(), App::assets()->createAssetOutputConfig(AssetOutputMode::INLINE));
            $logo = $data->getLogo($this->input->getLang(), $this->result->getAssetSetter());
            return ($logo !== null) ? $logo->generate($newGlobalConfig, $callerConfig) : '';
        });
            
        $table->createField('logo', $logoRenderer, $this->l('Logo'));
        $nameRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function($data){
            return $data->getProject()->getName();
        }));
        $table->createField('name', $nameRenderer, $this->l('Name'));
        
        $displayNameRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function($data){
            return $data->getDisplayName($this->input->getLang());
        }));
        $table->createField('displayName', $displayNameRenderer, $this->l('Display name'));
        
        $descriptionRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function($data){
            return $data->getDescription($this->input->getLang());
        }));
        $table->createField('description', $descriptionRenderer, $this->l('Description'));
        $listPanel->setInnerContent($table);
        $this->result->setContent($listPanel);
    }
    
    protected function processInstall(){
        $moduleName = $this->input->getParam('module_name');
        if(empty($moduleName)){
            $this->result->addError($this->l('Module name is required'));
        }else{
            $setups = $this->getUninstalledSetups($moduleName);
            if(isset($setups[0])){
                $installer = $setups[0]->getInstaller();
                if($installer !== null){
                    $installerResult = $installer->install(App::utils()->createSetupInput($this->input, $this->urlCreator, $this->result->getAssetSetter()));
                    $installOk = true;
                    if(($installerResult === null) || ($installerResult->isOperationExecuted() && $installerResult->isSuccessfullyExecuted())){
                        $installOk = $this->projectManager->install($setups[0]);
                    }
                    if($installOk){
                        $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::OTHER_CONTROLLER, 'module-management'));
                    }else{
                        $this->result->addError($this->l('An error occurred while installing the module'));
                    }
                }else{
                    $this->result->addError($this->l('Module is not installable'));
                }
            }else{
                $this->result->addError($this->l('Module is not installable'));
            }
        }
    }
    
    /**
     * @param string $moduleName
     * @return \muuska\project\setup\ProjectSetup[]
     */
    protected function getUninstalledSetups($moduleName = null){
        $result = array();
        foreach ($this->projectSetups as $setup) {
            $project = $setup->getProject();
            if(!$project->isInstalled() && ($project->getType() === ProjectType::MODULE)){
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