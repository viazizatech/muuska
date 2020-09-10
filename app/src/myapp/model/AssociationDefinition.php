<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;
use myapp\option\AccessibilityProvider;
class AssociationDefinition extends AbstractModelDefinition
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
            'name' => 'association',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            'fields' => array(
                
                'accessibility' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::OPTION,
                    'optionProvider' => new AccessibilityProvider()
                ),
                'nom_assoc' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::NAME,
                    'required' => true,
                    'lang' => true,
                    'maxSize' => 100
                ),
                'siege' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::NAME,
                    'maxSize' => 100
                ),
                'phone_respon' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::PHONE_NUMBER,
                    'required' => true,    
                ),
                'email_respon' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::EMAIL,
                    'maxSize' => 100
                ),
                'nom_admin' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::PERSON_NAME,
                    'required' => true,
                    'maxSize' => 100
                ),
                'prenom_admin' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::PERSON_NAME,
                    'required' => true,
                    'maxSize' => 100
                ),
                'logo' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::IMAGE,
                    'maxSize' => 50
                ),
                'devise' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::LONG_TEXT,
                    'maxSize' => 50,
                    'lang' => true
                ),
                'mot_pass' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::PASSWORD,
                    'required' => true
                ),
                'status' => array(
                    'type' => DataType::TYPE_STRING,
                    'nature' => FieldNature::FILE,
                    'maxSize' => 50
                )
             ),
            'associations' => array(
                'exercice' => array(
                    'reference' => ExerciceDefinition::getInstance(),
                    'field' => 'associationId'
                ),
                'tontine' => array(
                    'reference' => TontineDefinition::getInstance(),
                    'field' => 'associationId'
                )
                ,
                'membre' => array(
                    'reference' => TontineDefinition::getInstance(),
                    'field' => 'associationId'
                )
            )
        );
        
    }
    public function createModel()
    {
        return new Association();
    }
}