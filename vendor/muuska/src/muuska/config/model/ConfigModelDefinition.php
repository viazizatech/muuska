<?php
namespace muuska\config\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class ConfigModelDefinition extends AbstractModelDefinition{
    protected static $instance;
    
    /**
     * @return ConfigModelDefinition
     */
    public static function getInstance() {
        if(self::$instance === null){
            self::$instance = new static();
        }
        return self::$instance;
    }
    
	protected function createDefinition(){
		return array(
		    'projectType' => ProjectType::FRAMEWORK,
			'name' => 'config_model',
			'primary' => 'id',
			'autoIncrement' => true,
			'fields' => array(
			    'name' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::NAME, 'unique' => true, 'required' => true, 'maxSize' => 100),
				'value' => array('type' => DataType::TYPE_STRING),
				'lastModifiedDate' => array('type' => DataType::TYPE_DATETIME, 'nature' => FieldNature::OBJECT_LAST_MODIFIED_DATE)
			)
		);
	}
	
	public function createModel(){
	    return App::configs()->createConfigModel();
	}
}