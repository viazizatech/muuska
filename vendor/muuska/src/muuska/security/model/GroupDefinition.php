<?php
namespace muuska\security\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class GroupDefinition extends AbstractModelDefinition{
    protected static $instance;
    
    /**
     * @return GroupDefinition
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
			'name' => 'group',
			'primary' => 'id',
			'autoIncrement' => true,
			'multilingual' => true,
			'fields' => array(
			    'subAppName' => array('type' => DataType::TYPE_STRING, 'required' => true, 'maxSize' => 20),
				'name' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::NAME, 'required' => true, 'lang' => true, 'maxSize' => 50),
			    'parentId' => array('type' => DataType::TYPE_INT, 'nature' => FieldNature::EXISTING_MODEL_ID, 'reference' => static::getInstance(), 'onDelete' => ReferenceOption::CASCADE),
				'creationDate' => array('type' => DataType::TYPE_DATETIME, 'nature' => FieldNature::OBJECT_CREATION_DATE),
				'lastModifiedDate' => array('type' => DataType::TYPE_DATETIME, 'nature' => FieldNature::OBJECT_LAST_MODIFIED_DATE),
			),
		    'associations' => array(
		        'accesses' => array(
		            'reference' => GroupAccessDefinition::getInstance(),
		            'field' => 'groupId'
		        ),
		    )
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\model\AbstractModelDefinition::createModel()
	 */
	public function createModel() {
	    return App::securities()->createGroupModel();
	}
}