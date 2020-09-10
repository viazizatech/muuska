<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class EmpruntDefinition extends AbstractModelDefinition
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
            'name' => 'emprunt',
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
                'montant_emprunt' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'nature'=> FieldNature::PRICE,
                    'required' => true,
                    
                ), 
                'taux_emprunt' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'nature' => FieldNature::PERCENTAGE,
                    'required' => true,
                ),
                'date_emprunt' => array(
                    'type' => DataType::TYPE_DATE,
                    'validationRule' => 'isDate'
                )
            )
        );
    }
}