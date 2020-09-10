<?php
namespace muuska\dao;

class ModelDefinitionUpgradeInfo{
	/**
	 * @var \muuska\model\ModelDefinition
	 */
	protected $newModelDefinition;
	
	/**
	 * @var \muuska\model\ModelDefinition
	 */
	protected $oldModelDefinition;
	
	/**
	 * @param \muuska\model\ModelDefinition $oldModelDefinition
	 * @param \muuska\model\ModelDefinition $newModelDefinition
	 */
	public function __construct(\muuska\model\ModelDefinition $oldModelDefinition, \muuska\model\ModelDefinition $newModelDefinition){
	    $this->newModelDefinition = $newModelDefinition;
	    $this->oldModelDefinition = $oldModelDefinition;
	}
	
	/**
     * @return \muuska\model\ModelDefinition
     */
    public function getNewModelDefinition()
    {
        return $this->newModelDefinition;
    }

    /**
     * @return \muuska\model\ModelDefinition
     */
    public function getOldModelDefinition()
    {
        return $this->oldModelDefinition;
    }
}