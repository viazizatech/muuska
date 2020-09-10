<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class SeanceDefinition extends AbstractModelDefinition
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
            'name' => 'seance',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'exerciceId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => ExerciceDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'nom_seannce' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature'=> FieldNature::NAME,
                    'required' => true
                    
                ), 
                'date_seance' => array(
                    'type' => DataType::TYPE_DATE,
                    'validationRule' => 'isDate',
                    'required' => true
                    
                ),
                'heur_debut' => array(
                    'type' => DataType::TYPE_DATETIME,
                    'required' => true
                ), 
                'heur_fin' => array(
                    'type' => DataType::TYPE_DATETIME,
                    'required' => true
                )
            )
        );
    }
}