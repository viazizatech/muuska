<?php
namespace muuska\model;

interface ModelDefinition{
    
    /**
     * @return object
     */
    public function createModel();
    
    /**
     * @param string $field
     * @return ModelDefinition
     */
    public function getAssociationDefinition($field);
    
    /**
     * @param string $associationName
     * @return ModelDefinition
     */
    public function getMultipleAssociationDefinition($associationName);
    
    /**
     * @param string $associationName
     * @return string
     */
    public function getMultipleAssociationField($associationName);
    
    /**
     * @param object $model
     * @param \muuska\dao\DAO $dao
     * @param array $languages
     * @param string $defaultLang
     */
    public function formatFields(object $model, \muuska\dao\DAO $dao, $languages = array(), $defaultLang = '');
    
    /**
     * @param \muuska\validation\input\ModelValidationInput $input
     * @return \muuska\validation\result\ModelValidationResult
     */
    public function validateModel(\muuska\validation\input\ModelValidationInput $input);
    
    /**
     * @param string $field
     * @param \muuska\validation\input\ModelValidationInput $input
     * @return \muuska\validation\result\ValidationResult
     */
    public function validateField($field, \muuska\validation\input\ModelValidationInput $input);
    
    /**
     * @param \muuska\dao\DAO $dao
     * @param mixed $value
     * @param int $type
     * @param bool $with_quotes
     * @param bool $purify
     * @param bool $allow_null
     * @return mixed
     */
    public function formatValue(\muuska\dao\DAO $dao, $value, $type, $with_quotes = false, $purify = true, $allow_null = false);
	
    /**
     * @return bool
     */
    public function containsField($field);
    
    /**
     * @return array
     */
    public function getFieldDefinitions();
    
    /**
     * @return bool
     */
    public function hasForeignFields();
    
    /**
     * @return array
     */
    public function getFieldDefinition($field);
    
    /**
     * @return array
     */
    public function getFields();
    
	/**
	 * @return array
	 */
	public function getLangFields();
	
	/**
	 * @return @array
	 */
	public function getSimpleFields();
	
	/**
	 * @param string $field
	 * @return bool
	 */
	public function isLangField($field);
	
	/**
	 * @return bool
	 */
	public function isMultilingual();
	
	/**
	 * @return bool
	 */
	public function isAutoIncrement();
	
	/**
	 * @return string
	 */
	public function getName();
	
	/**
	 * @return string
	 */
	public function getFullName();
	
	/**
	 * @return string
	 */
	public function getSpecificDAOSource();
	
	/**
	 * @return bool
	 */
	public function hasSpecificDAOSource();
	
	/**
	 * @return string
	 */
	public function getPrimary();
	
	/**
	 * @return string
	 */
	public function getParentField();
	
	/**
	 * @return string
	 */
	public function getSinglePrimary();
	
	/**
	 * @return array
	 */
	public function getPrimaries();
	
	/**
	 * @return string[][]
	 */
	public function getMultipleUniques();
	
	/**
	 * @return bool
	 */
	public function hasMultiplePrimary();
	
	/**
	 * @param string $string
	 * @return array
	 */
	public function getPrimaryValuesFromString($string);
	
	/**
	 * @param object $model
	 * @return string
	 */
	public function getModelPresentation(object $model);
    
    /**
     * @return string
     */
    public function getObjectTypeForUpload();
	
	
	/**
	 * @return array
	 */
	public function getPresentationFields();
	
	/**
	 * @param string $currentField
	 * @return string
	 */
	public function getPresentationFieldsSeparator($currentField = '');
	
	/**
	 * @param bool $asExternal
	 * @param string $associatedFieldName
	 * @return \muuska\dao\util\FieldParameter
	 */
	public function createPresentationFieldParameter($asExternal = false, $associatedFieldName = '');
	
	/**
	 * @param \muuska\dao\util\FieldParameter $fieldParameter
	 * @param bool $asExternal
	 * @param string $associatedFieldName
	 * @return \muuska\dao\util\FieldParameter
	 */
	public function formatPresentationFieldParameter(\muuska\dao\util\FieldParameter $fieldParameter, $asExternal = false, $associatedFieldName = '');
	
	/**
	 * @param string $field
	 * @return string
	 */
	public function getSubFolderPath($field);
	
	/**
	 * @param string $field
	 * @return string
	 */
	public function getFilePath($field);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @return string
	 */
	public function getFileFullPath(object $model, $field);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @return bool
	 */
	public function hasImage(object $model, $field);
	
	/**
	 * @param object $model
	 * @return bool
	 */
	public function hasMainImage(object $model);
	
	/**
	 * @return string
	 */
	public function getMainImageField();
	
	/**
	 * @return \muuska\project\Project
	 */
	public function getProject();
	
	/**
	 * @param array $array
	 * @return object
	 */
	public function createModelFromArray($array);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @return mixed
	 */
	public function getPropertyValue(object $model, $field);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @param mixed $value
	 */
	public function setPropertyValue(object $model, $field, $value);
	
	/**
	 * @param object $model
	 * @param mixed $field
	 * @return bool
	 */
	public function hasAllLangPropertyValues(object $model, $field);
	
	/**
	 * @param string $field
	 * @param string $lang
	 * @return bool
	 */
	public function hasPropertyValueByLang(object $model, $field, $lang);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @return array
	 */
	public function getAllLangsPropertyValues(object $model, $field);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @param string $lang
	 * @return mixed
	 */
	public function getPropertyValueByLang(object $model, $field, $lang);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @param array $values
	 */
	public function setAllLangsPropertyValues(object $model, $field, $values);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @param mixed $value
	 * @param string $lang
	 */
	public function setPropertyValueByLang(object $model, $field, $value, $lang);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @param object $associatedobject
	 */
	public function setAssociatedModel(object $model, $field, object $associatedobject);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @return bool
	 */
	public function hasAssociatedModel(object $model, $field);
	
	/**
	 * @param object $model
	 * @param string $field
	 * @return object
	 */
	public function getAssociatedModel(object $model, $field);
	
	/**
	 * @param object $model
	 * @param string $associationName
	 * @param object $associatedModel
	 */
	public function addMultipleAssociated(object $model, $associationName, object $associatedModel);
	
	/**
	 * @param object $model
	 * @param string $associationName
	 * @param array $associatedModels
	 */
	public function setMultipleAssociatedModels(object $model, $associationName, $associatedModels);
	
	/**
	 * @param object $model
	 * @param string $associationName
	 * @return array
	 */
	public function getMultipleAssociatedModels(object $model, $associationName);
	
	/**
	 * @param object $model
	 * @return array
	 */
	public function toArray(object $model);
	
	/**
	 * @param object $model
	 * @return array
	 */
	public function getPrimaryValues(object $model);
	
	/**
	 * @param object $model
	 * @return string
	 */
	public function getPrimaryValue(object $model);
	
	/**
	 * @param object $model
	 * @return string
	 */
	public function getSinglePrimaryValue(object $model);
	
	/**
	 * @param object $model
	 * @param mixed $value
	 */
	public function setPrimaryValue(object $model, $value);
	
	/**
	 * @param object $model
	 * @return bool
	 */
	public function isLoaded(object $model);
	
	/**
	 * @param object $model
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @return bool
	 */
	public function isUpdateRequired(object $model, \muuska\dao\util\SaveConfig $saveConfig = null);
	
	/**
	 * @param object $model
	 * @return object
	 */
	public function duplicateModel(object $model);
	
	/**
	 * @param object $model
	 * @param \muuska\controller\ControllerInput $controllerInput
	 * @param string $action
	 * @param array $params
	 * @param string $anchor
	 * @param int $mode
	 * @return \muuska\url\UrlCreationInput
	 */
	public function createUrlInput(object $model, \muuska\controller\ControllerInput $controllerInput, $action, $params, $currentControllerNameEnabled = false, $anchor = '', $mode = null);
	
	/**
	 * @return string
	 */
	public function getCreationDateField();
	
	/**
	 * @return string
	 */
	public function getLastModifiedDateField();
	
	/**
	 * @return string
	 */
	public function getPositionField();
	
	/**
	 * @return bool
	 */
	public function isPositionFieldNeedsAutoValue();
	
	/**
	 * @return string
	 */
	public function getVirtualDeletionField();
	
	/**
	 * @return string
	 */
	public function getActivationField();
	
	/**
	 * @return string
	 */
	public function getStateField();
}