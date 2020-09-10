<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class InteretDefinition extends AbstractModelDefinition
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
            'name' => 'interet',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'uniques' => 'id' ,
            'fields' => array(
                'empruntId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => EmpruntDefinition ::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'type_interet' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::LONG_TEXT,
                    'required' => true,
                    'maxsize'=> 25
                    
                ),
                'montant_interet' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'required' => true,
                    'validationRule' => 'isPrice',  
                )
            )
        );
    }
}