<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class ProfilDefinition extends AbstractModelDefinition
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
            'name' => 'profil',
            'primary' => 'id',
            'autoIncrement' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'membreId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => MembreDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'parentId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'reference' => static::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'type_profil' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature'=> FieldNature::LONG_TEXT,
                    'maxSize' => 254,
                    
                ), 
                'poste_profil' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::LONG_TEXT,
                    'maxSize' => 254,
                )
                
            ),'presentationFields' => array(
                'type_profil',
                'poste_profil',
                
            ),
            'presentationFieldsSeparator' => ', '
        );
    }
}