<?php
namespace muuska\instantiator;

class Getters
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Getters
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $key
	 * @param \muuska\getter\Getter $finalGetter
	 * @return \muuska\getter\ArrayValueGetter
	 */
	public function createArrayValueGetter($key, \muuska\getter\Getter $finalGetter = null) {
	    return new \muuska\getter\ArrayValueGetter($key, $finalGetter);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param string $field
	 * @param \muuska\getter\Getter $finalModelGetter
	 * @return \muuska\getter\model\ModelValueGetter
	 */
	public function createModelValueGetter(\muuska\model\ModelDefinition $modelDefinition, $field, \muuska\getter\Getter $finalModelGetter = null) {
	    return new \muuska\getter\model\ModelValueGetter($modelDefinition, $field, $finalModelGetter);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param string $field
	 * @param \muuska\getter\Getter $finalModelGetter
	 * @return \muuska\getter\model\ModelAllLangsValueGetter
	 */
	public function createModelAllLangsValueGetter(\muuska\model\ModelDefinition $modelDefinition, $field, \muuska\getter\Getter $finalModelGetter = null) {
	    return new \muuska\getter\model\ModelAllLangsValueGetter($modelDefinition, $field, $finalModelGetter);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param string $field
	 * @param \muuska\getter\Getter $finalModelGetter
	 * @return \muuska\getter\model\AssociatedModelGetter
	 */
	public function createAssociatedModelGetter(\muuska\model\ModelDefinition $modelDefinition, $field, \muuska\getter\Getter $finalModelGetter = null) {
	    return new \muuska\getter\model\AssociatedModelGetter($modelDefinition, $field, $finalModelGetter);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param string $associationName
	 * @param \muuska\getter\Getter $finalModelGetter
	 * @return \muuska\getter\model\MultipleModelGetter
	 */
	public function createMultipleModelGetter(\muuska\model\ModelDefinition $modelDefinition, $associationName, \muuska\getter\Getter $finalModelGetter = null) {
	    return new \muuska\getter\model\MultipleModelGetter($modelDefinition, $associationName, $finalModelGetter);
	}
	
	/**
	 * @param \muuska\getter\Getter $finalGetter
	 * @return \muuska\getter\ToStringGetter
	 */
	public function createObjectToStringValueGetter(\muuska\getter\Getter $finalGetter = null) {
	    return new \muuska\getter\ToStringGetter($finalGetter);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param \muuska\getter\Getter $finalModelGetter
	 * @return \muuska\getter\model\ModelIdentifierGetter
	 */
	public function createModelIdentifierGetter(\muuska\model\ModelDefinition $modelDefinition, \muuska\getter\Getter $finalModelGetter = null) {
	    return new \muuska\getter\model\ModelIdentifierGetter($modelDefinition, $finalModelGetter);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param \muuska\getter\Getter $finalModelGetter
	 * @return \muuska\getter\model\ModelIdentifierGetter
	 */
	public function createModelPresentationGetter(\muuska\model\ModelDefinition $modelDefinition, \muuska\getter\Getter $finalModelGetter = null) {
	    return new \muuska\getter\model\ModelPresentationGetter($modelDefinition, $finalModelGetter);
	}
	
	/**
	 * @param \muuska\dao\DAO $dao
	 * @param \muuska\dao\util\SelectionConfig $selectionConfig
	 * @param \muuska\getter\Getter $finalModelGetter
	 * @return \muuska\getter\model\ChildrenModelGetter
	 */
	public function createChildrenModelGetter(\muuska\dao\DAO $dao, \muuska\dao\util\SelectionConfig $selectionConfig = null, \muuska\getter\Getter $finalModelGetter = null) {
	    return new \muuska\getter\model\ChildrenModelGetter($dao, $selectionConfig, $finalModelGetter);
	}
	
	/**
	 * @param \muuska\getter\Getter $finalGetter
	 * @return \muuska\getter\ArrayChildrenGetter
	 */
	public function createArrayChildrenGetter(\muuska\getter\Getter $finalGetter = null) {
	    return new \muuska\getter\ArrayChildrenGetter($finalGetter);
	}
	
	/**
	 * @param mixed $value
	 * @return \muuska\getter\StaticGetter
	 */
	public function createStaticValueGetter($value) {
	    return new \muuska\getter\StaticGetter($value);
	}
	
	/**
	 * @return \muuska\getter\SameGetter
	 */
	public function createSameValueGetter() {
	    return new \muuska\getter\SameGetter();
	}
	
	/**
	 * @return \muuska\getter\option\OptionValueGetter
	 */
	public function createOptionValueGetter() {
	    return new \muuska\getter\option\OptionValueGetter();
	}
	
	/**
	 * @return \muuska\getter\option\OptionLabelGetter
	 */
	public function createOptionLabelGetter() {
	    return new \muuska\getter\option\OptionLabelGetter();
	}
	
	/**
	 * @param int $options
	 * @param int $depth
	 * @param \muuska\getter\Getter $finalGetter
	 * @return \muuska\getter\JsonEncoder
	 */
	public function createJsonEncoder($options = null, $depth = null, \muuska\getter\Getter $finalGetter = null) {
	    return new \muuska\getter\JsonEncoder($options, $depth, $finalGetter);
	}
	
	/**
	 * @param bool $assoc
	 * @param int $depth
	 * @param int $options
	 * @param \muuska\getter\Getter $finalGetter
	 * @return \muuska\getter\JsonDecoder
	 */
	public function createJsonDecoder($assoc = null, $depth = null, $options = null, \muuska\getter\Getter $finalGetter = null) {
	    return new \muuska\getter\JsonDecoder($assoc, $depth, $options, $finalGetter);
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\getter\DefaultGetter
	 */
	public function createDefaultGetter($callback, $initialParams = null) {
	    return new \muuska\getter\DefaultGetter($callback, $initialParams);
	}
}
