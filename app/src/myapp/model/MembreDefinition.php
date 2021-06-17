<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;

class MembreDefinition extends AbstractModelDefinition
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
            'name' => 'membre',
            'primary' => 'id',
            'autoIncrement' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'nom' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::PERSON_NAME,
                    'required' => true,
                    'maxSize' => 100
                ),
                'prenom' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::PERSON_NAME,
                    'required' => true,
                    'maxSize' => 100
                ),
                'ville' => array(
                    'type' => DataType::TYPE_STRING,
                    'required' => true,
                    'validateRule' => 'isGenericName',
                    'maxSize' => 50
                ),
                'pays' => array(
                    'type' => DataType::TYPE_STRING,
                    'required' => true,
                    'validateRule' => 'isGenericName',
                    'maxSize' => 50
                ),
                'addresse' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::ADDRESS,
                    'required' => true,
                    'maxSize' => 100
                ),
                'photo' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::IMAGE,
                    'validationRule' => 'isGenericName',
                    'maxSize' => 50
                ),
                'email' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::EMAIL,
                    'maxSize' => 100
                ),
                'telephone' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::PHONE_NUMBER,
                    'required' => true,
                    'maxSize' => 100
                )    
            )
        );
    }
    
}