<?php
namespace muuska\security\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class AuthentificationGroupDefinition extends AbstractModelDefinition{
    protected static $instance;
    
    /**
     * @return AuthentificationGroupDefinition
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
			'name' => 'auth_group',
		    'primaries' => array('authentificationId', 'groupId'),
			'fields' => array(
			    'authentificationId' => array('type' => DataType::TYPE_INT, 'nature' => FieldNature::EXISTING_MODEL_ID, 'required' => true, 'reference' => App::securities()->getAuthentificationDefinitionInstance(), 'onDelete' => ReferenceOption::CASCADE),
			    'groupId' => array('type' => DataType::TYPE_INT, 'nature' => FieldNature::EXISTING_MODEL_ID, 'required' => true, 'reference' => App::securities()->getGroupDefinitionInstance(), 'onDelete' => ReferenceOption::CASCADE),
			),
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\model\AbstractModelDefinition::createModel()
	 */
	public function createModel() {
	    return App::securities()->createAuthentificationGroupModel();
	}
}