<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;
use muuska\util\App;

class AuthorDefinition extends AbstractModelDefinition
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
            'name' => 'author',
            'primary' => 'id',
            'autoIncrement' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'addressId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => AddressDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'firstName' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::PERSON_NAME,
                    'required' => true,
                    'maxSize' => 100
                ),
                'lastName' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::PERSON_NAME,
                    'required' => true,
                    'maxSize' => 100
                ),
                'gender' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::OPTION,
                    'optionProvider' => App::options()->createGenderProvider()
                ),
                'photo' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::IMAGE,
                    'maxSize' => 50
                ),
                'email' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::EMAIL,
                    'maxSize' => 100
                )
            )
        );
    }
}