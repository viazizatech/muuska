<?php
namespace muuska\localization\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\model\AbstractModelDefinition;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class LanguageModelDefinition extends AbstractModelDefinition{
    protected static $instance;
    
    /**
     * @return LanguageModelDefinition
     */
    public static function getInstance() {
        if(self::$instance === null){
            self::$instance = new static();
        }
        return self::$instance;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\AbstractModelDefinition::createDefinition()
     */
    protected function createDefinition(){
		return array(
		    'projectType' => ProjectType::FRAMEWORK,
			'name' => 'language',
			'primary' => 'id',
			'autoIncrement' => true,
		    'multilingual' => true,
			'fields' => array(
			    'uniqueCode' => array('type' => DataType::TYPE_STRING, 'maxSize' => 5),
				'active' => array('type' => DataType::TYPE_BOOL, 'nature' => FieldNature::STATUS, 'required' => true, 'default' => '1'),
			    'language' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::LANGUAGE_ISO_CODE, 'required' => true, 'maxSize' => 2),
			    'country' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::COUNTRY_ISO_CODE, 'required' => true),
			    'variant' => array('type' => DataType::TYPE_STRING),
			    'ISO3Language' => array('type' => DataType::TYPE_STRING, 'maxSize' => 3),
			    'ISO3Country' => array('type' => DataType::TYPE_STRING, 'maxSize' => 3),
			    'displayName' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::NAME, 'lang' => true),
			    'displayLanguage' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::NAME, 'lang' => true),
			    'displayCountry' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::NAME, 'lang' => true),
			    'displayVariant' => array('type' => DataType::TYPE_STRING, 'nature' => FieldNature::NAME, 'lang' => true)
			)
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\model\AbstractModelDefinition::createModel()
	 */
	public function createModel(){
	    return App::localizations()->createLanguageModel();
	}
}