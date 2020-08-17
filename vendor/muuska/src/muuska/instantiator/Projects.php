<?php
namespace muuska\instantiator;

class Projects
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Projects
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @return \muuska\project\model\ProjectModelDefinition
	 */
	public function getProjectModelDefinition() {
	    return \muuska\project\model\ProjectModelDefinition::getInstance();
	}
	
	/**
	 * @return \muuska\project\model\ProjectModel
	 */
	public function createProjectModel() {
	    return new \muuska\project\model\ProjectModel();
	}
	
	/**
	 * @param string $corePath
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @return \muuska\project\ProjectInput
	 */
	public function createProjectInput($corePath, \muuska\dao\DAOFactory $daoFactory) {
	    return new \muuska\project\ProjectInput($corePath, $daoFactory);
	}
	
	/**
	 * @param \muuska\project\ProjectInput $input
	 * @return \muuska\project\Framework
	 */
	public function createFramework(\muuska\project\ProjectInput $input) {
	    return new \muuska\project\Framework($input);
	}
	
	/**
	 * @param \muuska\project\Framework $framework
	 * @return \muuska\project\FrontFramework
	 */
	public function createFrontFramework(\muuska\project\Framework $framework) {
	    return new \muuska\project\FrontFramework($framework);
	}
	
	/**
	 * @param \muuska\project\Framework $framework
	 * @return \muuska\project\AdminFramework
	 */
	public function createAdminFramework(\muuska\project\Framework $framework) {
	    return new \muuska\project\AdminFramework($framework);
	}
	
	/**
	 * @param \muuska\project\Framework $framework
	 * @return \muuska\project\setup\FrameworkSetup
	 */
	public function createFrameworkSetup(\muuska\project\Framework $framework) {
	    return new \muuska\project\setup\FrameworkSetup($framework);
	}
	
	/**
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @return \muuska\project\setup\DefaultProjectManager
	 */
	public function createDefaultProjectManager(\muuska\dao\DAOFactory $daoFactory) {
	    return new \muuska\project\setup\DefaultProjectManager($daoFactory);
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\dao\ProjectDAOInstallInput $daoInstallInput
	 * @param callable $callback
	 * @param array $callbackInitialParams
	 * @return \muuska\project\setup\DefaultProjectInstaller
	 */
	public function createDefaultProjectInstaller(\muuska\project\Project $project, \muuska\dao\ProjectDAOInstallInput $daoInstallInput = null, $callback = null, $callbackInitialParams = null) {
	    return new \muuska\project\setup\DefaultProjectInstaller($project, $daoInstallInput, $callback, $callbackInitialParams);
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\dao\ProjectDAOUninstallInput $daoUninstallInput
	 * @param callable $callback
	 * @param array $callbackInitialParams
	 * @return \muuska\project\setup\DefaultProjectUninstaller
	 */
	public function createDefaultProjectUninstaller(\muuska\project\Project $project, \muuska\dao\ProjectDAOUninstallInput $daoUninstallInput = null, $callback = null, $callbackInitialParams = null) {
	    return new \muuska\project\setup\DefaultProjectUninstaller($project, $daoUninstallInput, $callback, $callbackInitialParams);
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @param \muuska\dao\ProjectDAOUpgradeInput $daoUpgradeInput
	 * @param boolean $eventChanged
	 * @param string[] $events
	 * @param callable $callback
	 * @param array $callbackInitialParams
	 * @return \muuska\project\setup\DefaultProjectUpgrade
	 */
	public function createDefaultProjectUpgrade(\muuska\project\Project $project, \muuska\dao\DAOFactory $daoFactory, \muuska\dao\ProjectDAOUpgradeInput $daoUpgradeInput = null, $eventChanged = false, $events = null, $callback = null, $callbackInitialParams = null) {
	    return new \muuska\project\setup\DefaultProjectUpgrade($project, $daoFactory, $daoUpgradeInput, $eventChanged, $events, $callback, $callbackInitialParams);
	}
}
