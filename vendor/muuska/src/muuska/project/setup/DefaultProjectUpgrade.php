<?php
namespace muuska\project\setup;

use muuska\util\App;
use muuska\util\FunctionCallback;

class DefaultProjectUpgrade extends FunctionCallback implements ProjectUpgrade
{
    /**
     * @var \muuska\project\Project
     */
    protected $project;
    
    /**
     * @var \muuska\dao\DAOFactory
     */
    protected $daoFactory;
    
    /**
     * @var \muuska\dao\ProjectDAOUpgradeInput
     */
    protected $daoUpgradeInput;
    
    /**
     * @var string[]
     */
    protected $events;
    
    /**
     * @var string
     */
    protected $token;
    
    /**
     * @var bool
     */
    protected $eventChanged;
    
    /**
     * @param \muuska\project\Project $project
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\dao\ProjectDAOUpgradeInput $daoUpgradeInput
     * @param boolean $eventChanged
     * @param array $events
     * @param callable $callback
     * @param array $callbackInitialParams
     */
    public function __construct(\muuska\project\Project $project, \muuska\dao\DAOFactory $daoFactory, \muuska\dao\ProjectDAOUpgradeInput $daoUpgradeInput = null, $eventChanged = false, $events = null, $callback = null, $callbackInitialParams = null) {
        $this->project = $project;
        $this->daoFactory = $daoFactory;
        $this->daoUpgradeInput = $daoUpgradeInput;
        $this->eventChanged = $eventChanged;
        $this->events = $events;
        
        if($callback !== null){
            $this->setCallback($callback);
            $this->setInitialParams($callbackInitialParams);
        }
        $this->generateToken();
    }
    
    protected function generateToken(){
        $this->token = md5(get_class($this).time().(int)$this->eventChanged.json_encode($this->events));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectUpgrade::upgrade()
     */
    public function upgrade(){
        $result = true;
        if(!$this->project->isUpToDate()){
            App::getFileTools()->copyAssets($this->project->getCoreDir(), $this->project->getSubPathInApp());
            if($this->daoUpgradeInput !== null){
                $result = $this->daoFactory->getSourceInstance($this->project)->upgradeProject($this->daoUpgradeInput);
            }
            if ($result && ($this->callback !== null)) {
                if(empty($this->initialParams)){
                    $callbackResult = call_user_func($this->callback);
                }else{
                    $callbackResult = call_user_func($this->callback, $this->initialParams);
                }
                if($callbackResult === false){
                    $result = false;
                }
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectUpgrade::getEvents()
     */
    public function getEvents()
    {
        return $this->events;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectUpgrade::getToken()
     */
    public function getToken()
    {
        return $this->token;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectUpgrade::isEventChanged()
     */
    public function isEventChanged()
    {
        return $this->eventChanged;
    }
}