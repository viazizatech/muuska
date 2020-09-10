<?php
namespace muuska\project\setup;

use muuska\util\App;
use muuska\util\setup\Uninstaller;
use muuska\util\FunctionCallback;

class DefaultProjectUninstaller extends FunctionCallback implements Uninstaller
{
    /**
     * @var \muuska\project\Project
     */
    protected $project;
    
    /**
     * @var \muuska\dao\ProjectDAOUninstallInput
     */
    protected $daoUninstallInput;
    
    /**
     * @param \muuska\project\Project $project
     * @param \muuska\dao\ProjectDAOUninstallInput $daoUninstallInput
     * @param callable $callback
     * @param array $callbackInitialParams
     */
    public function __construct(\muuska\project\Project $project, \muuska\dao\ProjectDAOUninstallInput $daoUninstallInput = null, $callback = null, $callbackInitialParams = null) {
        $this->project = $project;
        $this->daoUninstallInput = $daoUninstallInput;
        if($callback !== null){
            $this->setCallback($callback);
            $this->setInitialParams($callbackInitialParams);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\setup\Installer::uninstall()
     */
    public function uninstall(\muuska\util\setup\SetupInput $input){
        $result = null;
        if(!$this->project->isInstalled()){
            $boolResult = true;
            if($this->daoUninstallInput !== null){
                $boolResult = $input->getControllerInput()->getDaoFactory()->getSourceInstance($this->project)->uninstallProject($this->daoUninstallInput);
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