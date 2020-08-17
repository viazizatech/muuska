<?php
namespace muuska\security\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class GroupAccessDefinition extends AbstractModelDefinition{
    protected static $instance;
    
    /**
     * @return GroupAccessDefinition
     */
    public static function getInstance() {
        if(self::$instance === null){
            self::$instance = new static();
        }
        return self::$instance;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\AbstractModelDefinition::createDefinition()
     */
    protected function createDefinition(){
		return array(
		    'projectType' => ProjectType::FRAMEWORK,
			'name' => 'group_access',
		    'primaries' => array('groupId', 'resourceId'),
			'fields' => array(
			    'groupId' => array('type' => DataType::TYPE_INT, 'nature' => FieldNature::EXISTING_MODEL_ID, 'required' => true, 'reference' => App::securities()->getGroupDefinitionInstance()),
			    'resourceId' => array('type' => DataType::TYPE_INT, 'nature' => FieldNature::EXISTING_MODEL_ID, 'required' => true, 'reference' => App::securities()->getResourceDefinitionInstance()),
			),
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\model\AbstractModelDefinition::createModel()
	 */
	public function createModel() {
	    return App::securities()->createGroupAccessModel();
	}
}