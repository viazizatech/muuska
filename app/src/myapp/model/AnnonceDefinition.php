<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class AnnonceDefinition extends AbstractModelDefinition
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
            'name' => 'annonce',
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
                'auteur_annonce' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature'=> FieldNature::PERSON_NAME,
                    'required' => true,
                    
                ), 
                'date_annonce' => array(
                    'type' => DataType::TYPE_DATE,
                    'validationRule' => 'isDate',
                    'required' => true,
                    
                ),
                'contenu' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature'=> FieldNature::LONG_TEXT,
                    'required' => true,
                    'maxSize' => 254,
                    'lang' => true,
                )
            )
        );
    }
}