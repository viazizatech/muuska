<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class BookDefinition extends AbstractModelDefinition
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
            'name' => 'book',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'libraryId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => LibraryDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'authorId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => AuthorDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'categoryId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'reference' => CategoryDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'publisherId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'reference' => PublisherDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'code' => array(
                    'type' => DataType::TYPE_STRING,
                    'required' => true,
                    'validationRule' => 'isGenericName',
                    'maxSize' => 50
                ),
                'language' => array(
                    'type' => DataType::TYPE_STRING,
                    'validationRule' => 'isGenericName',
                    'maxSize' => 6
                ),
                'isbn' => array(
                    'type' => DataType::TYPE_STRING,
                    'validationRule' => 'isGenericName',
                    'maxSize' => 30
                ),
                'title' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::TITLE,
                    'required' => true,
                    'maxSize' => 254,
                    'lang' => true
                ),
                'publicationDate' => array(
                    'type' => DataType::TYPE_DATE,
                    'validationRule' => 'isDate'
                ),
                'image' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::IMAGE,
                    'maxSize' => 50
                ),
                'abstract' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::LONG_TEXT,
                    'lang' => true
                ),
                'digitalDocument' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::FILE,
                    'maxSize' => 50
                ),
                'numberOfPages' => array(
                    'type' => DataType::TYPE_INT,
                    'validate' => 'isUnsignedInt'
                ),
                'active' => array(
                    'type' => DataType::TYPE_BOOL,
                    'nature' => FieldNature::STATUS,
                    'default' => '1'
                )
            )
        );
    }
}