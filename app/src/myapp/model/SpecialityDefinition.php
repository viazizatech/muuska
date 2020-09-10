<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;

class SpecialityDefinition extends AbstractModelDefinition
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
            'name' => 'speciality',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'name' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::NAME,
                    'required' => true,
                    'maxSize' => 254,
                    'lang' => true
                ),
                'description' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::LONG_TEXT,
                    'lang' => true
                )
            )
        );
    }
}