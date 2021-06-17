<?php
namespace myapp\model;
use muuska\util\App;
use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\ReferenceOption;
use muuska\model\AbstractModelDefinition;

class EmpruntDefinition extends AbstractModelDefinition
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

       /* $callback = function (\muuska\validation\input\ValidationInput $input) {
            $result = null;
            if ($input->getValue() ==='50') {
            $result = App::validations()->createDefaultValidationResult(true);
            } else {
            $result = App::validations()->createDefaultValidationResult(
            false,
            [App::translateApp(App::createErrorTranslationConfig(),'My value is
            required',$input->getLang())]
            );
            }
            return $result;
            };
            $validator = App::validations()->createDefaultValidator($callback);
   $validator = App::validations()->createDefaultValidator($callback);*/
        return array(
            'name' => 'emprunt',
            'primary' => 'id',
            'autoIncrement' => true,
            'multilingual' => true,
            
            'fields' => array(
                'membreId' => array(
                    'type' => DataType::TYPE_INT,
                    'nature' => FieldNature::EXISTING_MODEL_ID,
                    'required' => true,
                    'reference' => MembreDefinition::getInstance(),
                    'onDelete' => ReferenceOption::CASCADE
                ),
                
                
                'montant_emprunt' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'nature'=> FieldNature::PRICE,
                    'required' => true,
                    //S'validator' => $validator
                ), 
                'taux_emprunt' => array(
                    'type' => DataType::TYPE_FLOAT,
                    'nature' => FieldNature::PERCENTAGE,
                    'required' => true,
                ),
                'date_emprunt' => array(
                    'type' => DataType::TYPE_DATE,
                    'validationRule' => 'isDate',
                    'required' => true
                ),
            )
        );
    }

    public function createModel()
    {
        return new Emprunt();
    }
}