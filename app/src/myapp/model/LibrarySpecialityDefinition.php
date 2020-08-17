<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class LibrarySpecialityDefinition extends AbstractModelDefinition
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
            'name' => 'library_speciality',
            'primaries' => array(
                'libraryId',
                'specialityId'
            ),
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'libraryId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => LibraryDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'specialityId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => SpecialityDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                )
            )
        );
    }
}