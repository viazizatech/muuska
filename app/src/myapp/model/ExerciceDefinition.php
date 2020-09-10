<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class ExerciceDefinition extends AbstractModelDefinition
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
            'name' => 'exercice',
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
                'nom_exercice' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature'=> FieldNature::NAME,
                    'required' => true,
                    
                ), 
                'date_debut' => array(
                    'type' => DataType::TYPE_DATE,
                    'validationRule' => 'isDate',
                    'required' => true,
                    
                ),
                'date_fin' => array(
                    'type' => DataType::TYPE_DATE,
                    'validationRule' => 'isDate',
                    'required' => true,
                ), 
                'taux_interet' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'nature' => FieldNature::PERCENTAGE,
                    'required' => true,
                ),
            )
        );
    }
}