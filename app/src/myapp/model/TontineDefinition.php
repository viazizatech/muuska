<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class TontineDefinition extends AbstractModelDefinition
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
            'name' => 'tontine',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'associationId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => AssociationDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'nom_tontine' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature'=> FieldNature::NAME,
                    'required' => true,
                    
                ), 
                'contribution' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'nature' => FieldNature::PRICE,
                    'required' => true,
                    
                )
            )
        );
    }
}