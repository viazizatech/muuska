<?php
namespace muuska\dao;

interface DAOSource{
	
	/**
	 * @return string
	 */
	public function getName();
	
	/**
	 * @param string $string
	 * @param bool $htmlOk
	 * @retur string
	 */
	public function protectString($string, $htmlOk = false);
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param \muuska\project\Project $project
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @return \muuska\dao\DAO
	 */
	public function createDefaultDAO(\muuska\model\ModelDefinition $modelDefinition, \muuska\project\Project $project, \muuska\dao\DAOFactory $daoFactory);
	
	/**
	 * @param ProjectDAOInstallInput $project
	 * @return bool
	 */
	public function installProject(ProjectDAOInstallInput $input);
	
	/**
	 * @param ProjectDAOUninstallInput $input
	 * @return bool
	 */
	public function uninstallProject(ProjectDAOUninstallInput $input);
	
	/**
	 * @param ProjectDAOUpgradeInput $input
	 * @return bool
	 */
	public function upgradeProject(ProjectDAOUpgradeInput $input);
}