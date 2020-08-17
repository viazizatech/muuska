<?php
namespace muuska\dao;

class ProjectDAOUpgradeInput{
	/**
	 * @var \muuska\project\Project
	 */
	protected $project;
	
	/**
	 * @var ModelDefinitionUpgradeInfo[]
	 */
	protected $modelDefinitionUpgradeInfos;
	
	/**
	 * @var \muuska\model\ModelDefinition[]
	 */
	protected $addedModelDefinitions;
	
	/**
	 * @var \muuska\model\ModelDefinition[]
	 */
	protected $removedModelDefinitions;
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\model\ModelDefinition[] $addedModelDefinitions
	 * @param \muuska\model\ModelDefinition[] $removedModelDefinitions
	 * @param ModelDefinitionUpgradeInfo[] $modelDefinitionUpgradeInfos
	 */
	public function __construct(\muuska\project\Project $project, $addedModelDefinitions = array(), $removedModelDefinitions = array(), $modelDefinitionUpgradeInfos = array()) {
	    $this->project = $project;
	    $this->setAddedModelDefinitions($addedModelDefinitions);
	    $this->setRemovedModelDefinitions($removedModelDefinitions);
	    $this->setModelDefinitionUpgradeInfos($modelDefinitionUpgradeInfos);
	}
	
    /**
     * @return \muuska\project\Project
     */
    public function getProject()
    {
        return $this->project;
    }
    
    public function getOldVersion()
    {
        $intalledInfo = $this->project->getInstalledInfo();
        return ($intalledInfo === null) ? $this->project->getVersion() : $intalledInfo->getVersion();
    }
    
    /**
     * @return bool
     */
    public function hasAddedModelDefinitions(){
        return !empty($this->addedModelDefinitions);
    }
    
    /**
     * @return bool
     */
    public function hasRemovedModelDefinitions(){
        return !empty($this->removedModelDefinitions);
    }
    
    /**
     * @return bool
     */
    public function hasModelDefinitionUpgradeInfos(){
        return !empty($this->modelDefinitionUpgradeInfos);
    }
    
    /**
     * @return \muuska\dao\ModelDefinitionUpgradeInfo[]
     */
    public function getModelDefinitionUpgradeInfos()
    {
        return $this->modelDefinitionUpgradeInfos;
    }

    /**
     * @return \muuska\model\ModelDefinition[]
     */
    public function getAddedModelDefinitions()
    {
        return $this->addedModelDefinitions;
    }

    /**
     * @return \muuska\model\ModelDefinition[]
     */
    public function getRemovedModelDefinitions()
    {
        return $this->removedModelDefinitions;
    }

    /**
     * @param \muuska\dao\ModelDefinitionUpgradeInfo[]  $modelDefinitionUpgradeInfos
     */
    public function setModelDefinitionUpgradeInfos($modelDefinitionUpgradeInfos)
    {
        $this->modelDefinitionUpgradeInfos = array();
        $this->addModelDefinitionUpgradeInfos($modelDefinitionUpgradeInfos);
    }
    
    /**
     * @param \muuska\dao\ModelDefinitionUpgradeInfo[]  $modelDefinitionUpgradeInfos
     */
    public function addModelDefinitionUpgradeInfos($modelDefinitionUpgradeInfos)
    {
        if(is_array($modelDefinitionUpgradeInfos)){
            foreach ($modelDefinitionUpgradeInfos as $modelDefinitionUpgradeInfo) {
                $this->addModelDefinitionUpgradeInfo($modelDefinitionUpgradeInfo);
            }
        }
    }
    
    /**
     * @param \muuska\dao\ModelDefinitionUpgradeInfo $modelDefinitionUpgradeInfo
     */
    public function addModelDefinitionUpgradeInfo(\muuska\dao\ModelDefinitionUpgradeInfo $modelDefinitionUpgradeInfo)
    {
        $this->modelDefinitionUpgradeInfos[] = $modelDefinitionUpgradeInfo;
    }
    
    /**
     * @param \muuska\model\ModelDefinition[]  $addedModelDefinitions
     */
    public function setAddedModelDefinitions($addedModelDefinitions)
    {
        $this->addedModelDefinitions = array();
        $this->addAddedModelDefinitions($addedModelDefinitions);
    }
    
    /**
     * @param \muuska\model\ModelDefinition[] $modelDefinitions
     */
    public function addAddedModelDefinitions($modelDefinitions)
    {
        if(is_array($modelDefinitions)){
            foreach ($modelDefinitions as $modelDefinition) {
                $this->addAddedModelDefinition($modelDefinition);
            }
        }
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     */
    public function addAddedModelDefinition(\muuska\model\ModelDefinition $modelDefinition)
    {
        $this->addedModelDefinitions[] = $modelDefinition;
    }

    /**
     * @param \muuska\model\ModelDefinition[]  $removedModelDefinitions
     */
    public function setRemovedModelDefinitions($removedModelDefinitions)
    {
        $this->removedModelDefinitions = array();
        $this->addRemovedModelDefinitions($removedModelDefinitions);
    }
    
    /**
     * @param \muuska\model\ModelDefinition[] $modelDefinitions
     */
    public function addRemovedModelDefinitions($modelDefinitions)
    {
        if(is_array($modelDefinitions)){
            foreach ($modelDefinitions as $modelDefinition) {
                $this->addRemovedModelDefinition($modelDefinition);
            }
        }
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     */
    public function addRemovedModelDefinition(\muuska\model\ModelDefinition $modelDefinition)
    {
        $this->removedModelDefinitions[] = $modelDefinition;
    }
}