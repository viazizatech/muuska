<?php
namespace myapp\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;

class UtilisateurDefinition extends AbstractModelDefinition
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
            'name' => 'utilisateur',
            'primary' => 'id',
            'autoIncrement' => true,
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
                )    
            )
        );
    }
    public function createModel(){
        return new Utilisateur();
    }
    
}