<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;

class AddressDefinition extends AbstractModelDefinition
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
            'name' => 'address',
            'primary' => 'id',
            'autoIncrement' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'address' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::ADDRESS,
                    'required' => true,
                    'maxSize' => 100
                ),
                'city' => array(
                    'type' => DataType::TYPE_STRING,
                    'required' => true,
                    'validateRule' => 'isGenericName',
                    'maxSize' => 50
                ),
                'country' => array(
                    'type' => DataType::TYPE_STRING,
                    'required' => true,
                    'validateRule' => 'isGenericName',
                    'maxSize' => 50
                ),
                'phone' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::PHONE_NUMBER,
                    'maxSize' => 30
                ),
                'state' => array(
                    'type' => DataType::TYPE_STRING,
                    'validateRule' => 'isGenericName',
                    'maxSize' => 50
                ),
                'postCode' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::POSTAL_CODE,
                    'maxSize' => 30
                )
            ),
            'presentationFields' => array(
                'postCode',
                'city',
                'state'
            ),
            'presentationFieldsSeparator' => ', '
        );
    }
}