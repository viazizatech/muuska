<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class SanctionDefinition extends AbstractModelDefinition
{

    protected static $instance;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    protected function createDefinition()
    {
        return array(
            'name' => 'sanction',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'membreId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => MembreDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'type_sanction' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature'=> FieldNature::TITLE,
                    'required' => true,
                    
                ), 
                'description_sanction' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::LONG_TEXT,
                    'required' => true,
                    'maxSize' => 254,
                    'lang' => true
                ),
                'amende' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'nature' => FieldNature::PRICE,
                    'required' => true,
                    
                )
            )
        );
    }
}