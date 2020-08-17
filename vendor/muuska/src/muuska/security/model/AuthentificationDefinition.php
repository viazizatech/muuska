<?php
namespace muuska\security\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class AuthentificationDefinition extends AbstractModelDefinition{
    protected static $instance;
    
    /**
     * @return AuthentificationDefinition
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
			'name' => 'auth',
			'primary' => 'id',
			'autoIncrement' => true,
			'fields' => array(
			    'subAppName' => array('type' => DataType::TYPE_STRING, 'required' => true, 'maxSize' => 20),
				'login' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::USERNAME, 'required' => true, 'maxSize' => 32),
				'password' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::PASSWORD, 'required' => true, 'maxSize' => 32),
				'active' => array('type' => DataType::TYPE_BOOL, 'nature' => FieldNature::STATUS, 'required' => true, 'default' => '1'),
				'superUser' => array('type' => DataType::TYPE_BOOL, 'required' => true, 'validate' => 'isBool', 'default' => '0'),
			    'preferredLang' => array('type' => DataType::TYPE_STRING, 'maxSize' => 5),
				'creationDate' => array('type' => DataType::TYPE_DATETIME, 'nature' => FieldNature::OBJECT_CREATION_DATE),
				'lastModifiedDate' => array('type' => DataType::TYPE_DATETIME, 'nature' => FieldNature::OBJECT_LAST_MODIFIED_DATE),
				'deleted' => array('type' => DataType::TYPE_BOOL, 'nature' => FieldNature::VIRTUAL_DELETION_FIELD, 'default' => '0')
			),
			'presentationFields' => array('login'),
			'uniques' => array(array('subAppName', 'login')),
		    'associations' => array(
		        'groups' => array(
		            'reference' => AuthentificationGroupDefinition::getInstance(),
		            'field' => 'authentificationId'
		        ),
		        'accesses' => array(
		            'reference' => AuthentificationAccessDefinition::getInstance(),
		            'field' => 'authentificationId'
		        ),
		    )
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\model\AbstractModelDefinition::createModel()
	 */
	public function createModel() {
	    return App::securities()->createAuthentificationModel();
	}
}