<?php
namespace muuska\project\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class ProjectModelDefinition extends AbstractModelDefinition{
    protected static $instance;
    
    /**
     * @return ProjectModelDefinition
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
		    'projectType' => ProjectType::APPLICATION,
			'name' => 'project',
			'primary' => 'id',
			'autoIncrement' => true,
			'fields' => array(
			    'type' => array('type' => DataType::TYPE_STRING, 'required' => true, 'maxSize' => 20),
			    'name' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::NAME, 'maxSize' => 50),
				'active' => array('type' => DataType::TYPE_BOOL, 'nature' => FieldNature::STATUS, 'required' => true, 'default' => '1'),
				'version' => array('type' => DataType::TYPE_STRING, 'required' => true, 'maxSize' => 8),
				'eventString' => array('type' => DataType::TYPE_STRING),
				'mainClass' => array('type' => DataType::TYPE_STRING, 'required' => true, 'maxSize' => 100),
				'lastUpgradeDate' => array('type' => DataType::TYPE_DATETIME),
				'creationDate' => array('type' => DataType::TYPE_DATETIME, 'nature' => FieldNature::OBJECT_CREATION_DATE),
				'lastModifiedDate' => array('type' => DataType::TYPE_DATETIME, 'nature' => FieldNature::OBJECT_LAST_MODIFIED_DATE)
			),
		    'uniques' => array(
		        array('type', 'name')
		    )
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\model\AbstractModelDefinition::createModel()
	 */
	public function createModel(){
	    return App::projects()->createProjectModel();
	}
}