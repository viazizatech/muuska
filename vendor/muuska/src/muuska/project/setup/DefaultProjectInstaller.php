<?php
namespace muuska\project\setup;

use muuska\util\App;
use muuska\util\FunctionCallback;
use muuska\util\setup\Installer;

class DefaultProjectInstaller extends FunctionCallback implements Installer
{
    /**
     * @var \muuska\project\Project
     */
    protected $project;
    
    /**
     * @var \muuska\dao\ProjectDAOInstallInput
     */
    protected $daoInstallInput;
    
    /**
     * @param \muuska\project\Project $project
     * @param \muuska\dao\ProjectDAOInstallInput $daoInstallInput
     * @param callable $callback
     * @param array $callbackInitialParams
     */
    public function __construct(\muuska\project\Project $project, \muuska\dao\ProjectDAOInstallInput $daoInstallInput = null, $callback = null, $callbackInitialParams = null) {
        $this->project = $project;
        $this->daoInstallInput = $daoInstallInput;
        if($callback !== null){
            $this->setCallback($callback);
            $this->setInitialParams($callbackInitialParams);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\setup\Installer::install()
     */
    public function install(\muuska\util\setup\SetupInput $input){
        $result = null;
        if(!$this->project->isInstalled()){
            App::getFileTools()->copyAssets($this->project->getCoreDir(), $this->project->getSubPathInApp());
            $boolResult = true;
            if($this->daoInstallInput !== null){
                $boolResult = $input->getControllerInput()->getDaoFactory()->getSourceInstance($this->project)->installProject($this->daoInstallInput);
            }
            
            if ($boolResult && ($this->callback !== null)) {
                if(empty($this->initialParams)){
                    $callbackResult = call_user_func($this->callback, $input);
                }else{
                    $callbackResult = call_user_func($this->callback, $this->initialParams, $input);
                }
                if($callbackResult === false){
                    $boolResult = false;
                }
            }
            $result = App::utils()->createDefaultNavigationResult(true, $boolResult);
        }
        return $result;
    }
}