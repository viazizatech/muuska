<?php
namespace muuska\model;

use muuska\util\Collection;
use muuska\util\App;

class ModelCollection extends Collection
{	
	/**
	 * @var \muuska\model\ModelDefinition
	 */
	protected $modelDefinition;

	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param array $data
	 */
	public function __construct(\muuska\model\ModelDefinition $modelDefinition, array $data = array()) {
	    parent::__construct($data);
	    $this->modelDefinition = $modelDefinition;
    }
    
    /**
     * @param array $array
     * @param \muuska\getter\Getter $valueGetter
     * @param \muuska\getter\Getter $labelGetter
     * @return \muuska\option\provider\AbstractOptionProvider
     */
    public function toOptionProvider($valueGetter = null, $labelGetter = null) {
        $optionProvider = null;
        if(($valueGetter !== null) && ($labelGetter !== null)){
            $optionProvider = App::getArrayTools()->getOptionProvider($this->data, $valueGetter, $labelGetter);
        }else {
            $optionProvider = App::getArrayTools()->getOptionProvider($this->data, App::getters()->createModelIdentifierGetter($this->modelDefinition), App::getters()->createModelPresentationGetter($this->modelDefinition));
        }
        return $optionProvider;
    }
    
    /**
     * @param \muuska\getter\Getter $valueGetter
     * @return array
     */
    public function getArrayValues(\muuska\getter\Getter $valueGetter) {
        return App::getArrayTools()->getArrayValues($this->data, $valueGetter);
    }
    
    /**
     * @param string $field
     * @return array
     */
    public function getArrayValuesFromField($field) {
        return $this->getArrayValues(App::getters()->createModelValueGetter($this->modelDefinition, $field));
    }
    
    /**
     * @param string $field
     * @return array
     */
    public function getGroupedArray($field) {
        $result = array();
        foreach ($this->data as $model) {
            $result[$this->modelDefinition->getPropertyValue($model, $field)][] = $model;
        }
        return $result;
    }
    
    /**
     * @param array $associationsData
     */
    public function setMultipleAssociationsData($associationsData) {
        foreach ($this->data as $model) {
            $modelId = $this->modelDefinition->getSinglePrimaryValue($model);
            foreach ($associationsData as $associationName => $associationGroupedData) {
                if(isset($associationGroupedData[$modelId])){
                    $this->modelDefinition->setMultipleAssociatedModels($model, $associationName, $associationGroupedData[$modelId]);
                }
            }
        }
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param \muuska\getter\Getter $associatedModelGetter
     * @param bool $excludeNullObject
     * @return \muuska\model\ModelCollection
     */
    public function getAssociatedCollection(\muuska\model\ModelDefinition $modelDefinition, \muuska\getter\Getter $associatedModelGetter, $excludeNullObject = true) {
        $newModels = array();
        foreach ($this->data as $model) {
            $associatedModel = $associatedModelGetter->get($model);
            if(!$excludeNullObject || ($associatedModel !== null)){
                $newModels[] = $associatedModel;
            }
        }
        return App::models()->createModelCollection($modelDefinition, $newModels);
    }
    
    /**
     * @param string $field
     * @param bool $excludeNullObject
     * @return \muuska\model\ModelCollection
     */
    public function getAssociatedCollectionFromField($field, $excludeNullObject = true) {
        return $this->getAssociatedCollection($this->modelDefinition->getAssociationDefinition($field), App::getters()->createAssociatedModelGetter($this->modelDefinition, $field), $excludeNullObject);
    }
    
    /**
     * @return \muuska\model\ModelDefinition
     */
    public function getModelDefinition()
    {
        return $this->modelDefinition;
    }
}
