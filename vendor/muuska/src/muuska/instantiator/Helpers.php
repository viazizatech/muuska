<?php
namespace muuska\instantiator;

class Helpers
{
	private static $instance;
	protected function __construct(){
		
	}
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	public function createModelListHelper(\muuska\controller\ControllerInput $input, \muuska\controller\param\ControllerParamResolver $paramResolver, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\dao\DAO $dao, $recorderKey, $externalFieldsDefinition = array(), \muuska\dao\util\SelectionConfig $selectionConfig = null) {
	    return new \muuska\helper\ModelListHelper($input, $paramResolver, $urlCreator, $dao, $recorderKey, $externalFieldsDefinition, $selectionConfig);
	}
	public function createModelCrudListHelper(\muuska\controller\ControllerInput $input, \muuska\controller\param\ControllerParamResolver $paramResolver, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\dao\DAO $dao, $recorderKey, $externalFieldsDefinition = array(), \muuska\dao\util\SelectionConfig $selectionConfig = null) {
	    return new \muuska\helper\ModelCrudListHelper($input, $paramResolver, $urlCreator, $dao, $recorderKey, $externalFieldsDefinition, $selectionConfig);
	}
	public function createModelFormHelper(\muuska\controller\ControllerInput $input, \muuska\controller\param\ControllerParamResolver $paramResolver, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\dao\DAO $dao, $update, $externalFieldsDefinition = array(), \muuska\dao\util\SelectionConfig $loadedModelSelectionConfig = null) {
	    return new \muuska\helper\ModelFormHelper($input, $paramResolver, $urlCreator, $dao, $update, $externalFieldsDefinition, $loadedModelSelectionConfig);
	}
	
	/**
	 * @param \muuska\controller\ControllerInput $input
	 * @param string[] $allowedExtensions
	 * @param string[] $excludedExtensions
	 * @return \muuska\helper\UploadHelper
	 */
	public function createUploadHelper(\muuska\controller\ControllerInput $input, $allowedExtensions = array(), $excludedExtensions = array()) {
	    return new \muuska\helper\UploadHelper($input, $allowedExtensions, $excludedExtensions);
	}
	
	public function createModelCrudViewHelper(\muuska\controller\ControllerInput $input, \muuska\controller\param\ControllerParamResolver $paramResolver, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\dao\DAO $dao, $externalFieldsDefinition = array(), \muuska\dao\util\SelectionConfig $selectionConfig = null) {
	    return new \muuska\helper\ModelCrudViewHelper($input, $paramResolver, $urlCreator, $dao, $externalFieldsDefinition, $selectionConfig);
	}
}
