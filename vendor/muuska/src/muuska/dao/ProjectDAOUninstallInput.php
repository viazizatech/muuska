<?php
namespace muuska\dao;

class ProjectDAOUninstallInput{
	/**
	 * @var \muuska\project\Project
	 */
	protected $project;
	
	/**
	 * @var \muuska\model\ModelDefinition[]
	 */
	protected $modelDefinitions;
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\model\ModelDefinition[] $modelDefinitions
	 */
	public function __construct(\muuska\project\Project $project, $modelDefinitions = array()) {
	    $this->project = $project;
	    $this->setModelDefinitions($modelDefinitions);
	}
	
	/**
	 * @return bool
	 */
	public function hasModelDefinitions(){
	    return !empty($this->modelDefinitions);
	}
	
    /**
     * @return \muuska\project\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return \muuska\model\ModelDefinition[]
     */
    public function getModelDefinitions()
    {
        return $this->modelDefinitions;
    }

    /**
     * @param \muuska\model\ModelDefinition[] $modelDefinitions
     */
    public function setModelDefinitions($modelDefinitions)
    {
        $this->modelDefinitions = array();
        $this->addModelDefinitions($modelDefinitions);
    }
    
    /**
     * @param \muuska\model\ModelDefinition[] $modelDefinitions
     */
    public function addModelDefinitions($modelDefinitions)
    {
        if(is_array($modelDefinitions)){
            foreach ($modelDefinitions as $modelDefinition) {
                $this->addModelDefinition($modelDefinition);
            }
        }
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     */
    public function addModelDefinition(\muuska\model\ModelDefinition $modelDefinition)
    {
        $this->modelDefinitions[] = $modelDefinition;
    }
}