<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class AssociationMembreDefinition extends AbstractModelDefinition
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
            'name' => 'etre_membre',
            'primaries' => array(
                'membreId',
                'associationId'
            ),
            'modelType' => self::MODEL_TYPE_ARRAY,
            'fields' => array(
                'associationId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => AssociationDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'membreId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => MembreDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                'date_adhesion' => array(
                    'type' => DataType::TYPE_DATE,
                    'validationRule' => 'isDate'
                )
            )
        );
    }
}