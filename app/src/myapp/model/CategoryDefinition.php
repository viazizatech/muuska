<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class CategoryDefinition extends AbstractModelDefinition
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
            'name' => 'category',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'parentId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'reference' => static::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'name' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::NAME,
                    'required' => true,
                    'maxSize' => 200,
                    'lang' => true
                ),
                'image' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::IMAGE,
                    'maxSize' => 50
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