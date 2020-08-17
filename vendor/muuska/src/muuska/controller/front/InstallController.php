<?php
namespace muuska\controller\front;

use muuska\controller\AbstractController;
use muuska\http\constants\RedirectionType;
use muuska\project\setup\ProjectSetupManager;
use muuska\util\App;
use muuska\project\constants\SubAppName;

class InstallController extends AbstractController implements ProjectSetupManager
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
        App::getApp()->initSetupManager($this);
        if(App::getApp()->isAppInstalled()){
            $this->result->addError($this->l('Application is already installed'));
        }
    }
    
    protected function processDefault(){
        $index = (int)$this->input->getRequest()->getQueryParam('installer_index', null);
        $totalItems = count($this->projectSetups);
        if(isset($this->projectSetups[$index])){
            $installer = $this->projectSetups[$index]->getInstaller();
            if($installer !== null){
                $installerResult = $installer->install(App::utils()->createSetupInput($this->input, $this->urlCreator, $this->result->getAssetSetter()));
                if(($installerResult === null) || ($installerResult->isOperationExecuted() && $installerResult->isSuccessfullyExecuted())){
                    $this->projectManager->install($this->projectSetups[$index]);
                }
            }
        }
        $nextIndex = $index + 1;
        if($nextIndex < $totalItems){
            $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::SAME_ACTION, null, null, array('installer_index' => $nextIndex)));
        }else{
            $this->installThemes();
            $this->createSuperUser();
            $mainConfig = App::getApp()->getMainConfiguration();
            $mainConfig->setProperty('app_installed', true);
            $mainConfig->save();
            $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::HOME));
        }
    }
    
    protected function installThemes()
    {
        $enabledSubApps = App::getApp()->getEnabledSubApplications();
        $installedThemes = array();
        foreach ($enabledSubApps as $enabledSubApp) {
            $subAppInstance = App::getApp()->getSubApplication($enabledSubApp);
            $subAppTheme = ($subAppInstance !== null) ? $subAppInstance->getActiveTheme() : null;
            if(($subAppInstance !== null)){
                $themeName = $subAppInstance->getActiveThemeName();
                $subAppTheme = $subAppInstance->getActiveTheme();
                if(($subAppTheme !== null) && !isset($installedThemes[$themeName])){
                    App::utils()->createDefaultThemeInstaller($subAppTheme)->install(App::utils()->createSetupInput($this->input, $this->urlCreator, $this->result->getAssetSetter()));
                    $installedThemes[$themeName] = $subAppTheme;
                }
            }
        }
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
    
    protected function createSuperUser() {
        $user = App::securities()->createAuthentificationModel();
        $user->setLogin('demo@demo.com');
        $user->setPassword($this->input->getCurrentUser()->encryptPassword('demo'));
        $user->setActive(true);
        $user->setPreferredLang($this->input->getLang());
        $user->setSubAppName(SubAppName::BACK_OFFICE);
        $user->setSuperUser(true);
        $this->input->getDAO(App::securities()->getAuthentificationDefinitionInstance())->add($user);
    }
}