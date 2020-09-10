<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class RemboursementDefinition extends AbstractModelDefinition
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
            'name' => 'remboursement',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'unique' => 'id' ,
            'fields' => array(
                'membreId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => MembreDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'empruntId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => EmpruntDefinition ::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'date_rembours' => array(
                    'type' => DataType::TYPE_DATE,
                    'required' => true,
                    'validationRule' => 'isDate',
                ),
                'montant_rembours' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'required' => true,
                    'validationRule' => 'isPrice',  
                ),
                'reste' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'nature' => FieldNature::PRICE,
                    'maxSize' => 30
                )
            )
        );
    }
}