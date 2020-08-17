<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;
use myapp\option\AccessibilityProvider;
use muuska\dao\constants\ReferenceOption;
class LibraryDefinition extends AbstractModelDefinition
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
            'name' => 'library',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'fields' => array(
                'addressId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => AddressDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'name' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::NAME,
                    'required' => true,
                    'maxSize' => 200,
                    'unique' => true,
                    'lang' => true
                ),
                'openingTime' => array(
                    'type' => DataType::TYPE_STRING,
                    'validateRule' => 'isGenericName',
                    'maxSize' => 254,
                    'lang' => true
                ),
                'accessibility' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::OPTION,
                    'optionProvider' => new AccessibilityProvider()
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
            ),
            'associations' => array(
                'books' => array(
                    'reference' => BookDefinition::getInstance(),
                    'field' => 'libraryId'
                ),
                'specialities' => array(
                    'reference' => LibrarySpecialityDefinition::getInstance(),
                    'field' => 'libraryId'
                ),
                'types' => array(
                    'reference' => LibraryTypeDefinition::getInstance(),
                    'field' => 'libraryId'
                ),
            )
        );
    }
    public function createModel()
    {
        return new Library();
    }
}