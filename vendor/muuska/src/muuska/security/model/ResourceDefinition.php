<?php
namespace muuska\security\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;
use muuska\project\constants\ProjectType;
use muuska\util\App;
use muuska\security\constants\ResourceAccessRule;

class ResourceDefinition extends AbstractModelDefinition{
    protected static $instance;
    
    /**
     * @return ResourceDefinition
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
			'name' => 'resource',
			'primary' => 'id',
			'autoIncrement' => true,
			'multilingual' => true,
			'fields' => array(
				'code' => array('type' => DataType::TYPE_STRING, 'required' => true, 'maxSize' => 100),
			    'parentId' => array('type' => DataType::TYPE_INT, 'nature' => FieldNature::EXISTING_MODEL_ID, 'reference' => static::getInstance(), 'onDelete' => ReferenceOption::CASCADE),
			    'accessRule' => array('type' => DataType::TYPE_INT, 'default' => ResourceAccessRule::AUTHORIZATION, 'nature' => FieldNature::OPTION, 'optionProvider' => App::securities()->createResourceAccessRuleOptionProvider()),
				'label' => array('type' => DataType::TYPE_STRING, 'lang' => true, 'maxSize' => 250),
			    'creationDate' => array('type' => DataType::TYPE_DATETIME, 'nature' => FieldNature::OBJECT_CREATION_DATE),
			    'lastModifiedDate' => array('type' => DataType::TYPE_DATETIME, 'nature' => FieldNature::OBJECT_LAST_MODIFIED_DATE)
			),
		    'uniques' => array(array('parentId', 'code'))
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\model\AbstractModelDefinition::createModel()
	 */
	public function createModel() {
	    return App::securities()->createResourceModel();
	}
}