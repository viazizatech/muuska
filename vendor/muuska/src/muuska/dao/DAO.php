<?php
namespace muuska\dao;

interface DAO{
    /**
     * @return \muuska\model\ModelDefinition
     */
    public function getModelDefinition();
	
    /**
     * Save object
     *
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @throws \muuska\dao\exception\InvalidModelException
     * @return bool
     */
    public function save(object $model, \muuska\dao\util\SaveConfig $saveConfig = null);
	
    /**
     * Add object
     *
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @throws \muuska\dao\exception\InvalidModelException
     * @return bool
     */
    public function add(object $model, \muuska\dao\util\SaveConfig $saveConfig = null);
	
    /**
     * Update object
     *
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @throws \muuska\dao\exception\InvalidModelException
     * @return bool
     */
    public function update(object $model, \muuska\dao\util\SaveConfig $saveConfig = null);
    
    /**
     * Update multiples rows with a model data
     *
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @throws \muuska\dao\exception\InvalidModelException
     * @return bool
     */
    public function updateMultipleRows(object $model, \muuska\dao\util\SaveConfig $saveConfig = null);
    
    /**
     * Update multiples rows with a array data
     *
     * @param array $data
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @throws \muuska\dao\exception\InvalidModelException
     * @return bool
     */
    public function updateMultipleRowsFromArray($data, \muuska\dao\util\SaveConfig $saveConfig = null);
    
    
    /**
     * Activate object
     *
     * @param object $model
     * @throws \muuska\dao\exception\InvalidModelException
     * @return bool
     */
    public function activate(object $model);
    
    /**
     * Deactivate object
     *
     * @param object $model
     * @throws \muuska\dao\exception\InvalidModelException
     * @return bool
     */
    public function deactivate(object $model);
    
    /**
     * Change model field value
     *
     * @param object $model
     * @param string $field
     * @param mixed $value
     * @throws \muuska\dao\exception\InvalidModelException
     * @return bool
     */
    public function changeValue(object $model, $field, $value);
    
    /**
     * Change field value on multiple rows
     *
     * @param string $field
     * @param mixed $value
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @throws \muuska\dao\exception\InvalidModelException
     * @return bool
     */
    public function changeValueOnMultipleRows($field, $value, \muuska\dao\util\SaveConfig $saveConfig = null);
    
    /**
     * Delete object
     *
     * @param object $model
     * @param \muuska\dao\util\DeleteConfig $deleteConfig
     * @return bool
     */
    public function delete(object $model, \muuska\dao\util\DeleteConfig $deleteConfig = null);

    /**
     * Delete mutiple rows
     *
     * @param \muuska\dao\util\DeleteConfig $deleteConfig
     * @return bool
     */
    public function deleteMultipleRows(\muuska\dao\util\DeleteConfig $deleteConfig = null);
    
    /**
     * Delete all data
     *
     * @return bool
     */
    public function clearData();
    
    /**
     * Get a model by id
     *
     * @param mixed $id
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @param bool $returnEmptyModelIfNotFound
     * @return object
     */
    public function getById($id, \muuska\dao\util\SelectionConfig $selectionConfig = null, $returnEmptyModelIfNotFound = false);
    
    /**
     * Get a model
     *
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @param bool $returnEmptyModelIfNotFound
     * @return object
     */
    public function getUniqueModel(\muuska\dao\util\SelectionConfig $selectionConfig, $returnEmptyModelIfNotFound = false);
    
    /**
     * Get a model field value
     *
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @param string $field
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getModelValue(\muuska\dao\util\SelectionConfig $selectionConfig, $field, $defaultValue = null);
	
	/**
     * Get list of model
     *
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @return \muuska\dao\util\DAOListResult
     */
    public function getData(\muuska\dao\util\SelectionConfig $selectionConfig = null);
    
    
    /**
     * Get total of data object
     *
     * @param array $fields
     * @return int
     */
    public function getDataTotal(\muuska\dao\util\SelectionConfig $selectionConfig = null);
	
    /**
     * @param object $model
     * @param array $fields
     * @param bool $update
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @return bool
     */
    public function checkUnique(object $model, $fields, $update = false, \muuska\dao\util\SaveConfig $saveConfig = null);
    
    /**
     * @param object $model
     */
    public function setModelNewPosition(object $model);
	
    /**
     * @return object
     */
    public function createModel();
	
	/**
	 * @return string
	 */
	public function getDefaultLang();
	
	/**
	 * @param \muuska\dao\util\DataConfig $dataConfig
	 * @return string
	 */
	public function getLang(\muuska\dao\util\DataConfig $dataConfig = null);
	
	/**
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @return \muuska\localization\LanguageInfo[]
	 */
	public function getLanguages(\muuska\dao\util\SaveConfig $saveConfig = null);
	
	/**
	 * @param string $field
	 * @return DAO
	 */
	public function getForeignDAO($field);
    
    /**
     * @param string $associationName
     * @return DAO
     */
	public function getMultipleAssociationDAO($associationName);
	
    /**
     * @param object $model
     * @param string $field
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @param bool $returnEmptyModelIfNotFound
     * @return object
     */
	public function loadAssociatedObject(object $model, $field, \muuska\dao\util\SelectionConfig $selectionConfig = null, $returnEmptyModelIfNotFound = false);
	
	/**
	 * @param object $model
	 * @param string $associationName
	 * @param \muuska\dao\util\SelectionConfig $selectionConfig
	 * @return \muuska\dao\util\DAOListResult
	 */
	public function loadMultipleAssociatedObjects(object $model, $associationName, \muuska\dao\util\SelectionConfig $selectionConfig = null);
	
	/**
	 * @param object $model
	 * @param \muuska\dao\util\SelectionConfig $selectionConfig
	 * @return \muuska\model\ModelCollection
	 */
	public function getParents(object $model, \muuska\dao\util\SelectionConfig $selectionConfig = null);
    
	/**
	 * @param object $model
	 * @param \muuska\dao\util\SelectionConfig $selectionConfig
	 * @return \muuska\model\ModelCollection
	 */
	public function getChildren(object $model, \muuska\dao\util\SelectionConfig $selectionConfig = null);
	
	/**
	 * @param string $string
	 * @param bool $html_ok
	 * @retur string
	 */
	public function protectString($string, $html_ok = false);
	
	/**
	 * @param string $fieldName
	 * @param mixed $value
	 * @param int $operator
	 * @return \muuska\dao\util\FieldRestriction
	 */
	public function createFieldRestriction($fieldName, $value, $operator = null);
	
	/**
	 * @param string $lang
	 * @param array $languages
	 * @return \muuska\dao\util\SaveConfig
	 */
	public function createSaveConfig($lang = null, $languages = array());
	
	/**
	 * @param string $fieldName
	 * @param bool $langEnabled
	 * @param bool $allLangsEnabled
	 * @param int $joinType
	 * @param bool $retrievingEnabled
	 * @return \muuska\dao\util\SelectionAssociation
	 */
	public function createSelectionAssociation($fieldName, $langEnabled = true, $allLangsEnabled = false, $joinType = null, $retrievingEnabled = true);
	
	/**
	 * @param string $lang
	 * @return \muuska\dao\util\SelectionConfig
	 */
	public function createSelectionConfig($lang = null);
	
	/**
	 * @param bool $virtual
	 * @return \muuska\dao\util\DeleteConfig
	 */
	public function createDeleteConfig($virtual = true);
	
	/**
	 * @return \muuska\project\Project
	 */
	public function getProject();
}