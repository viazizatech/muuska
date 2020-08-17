<?php
namespace muuska\dao;

use muuska\constants\operator\LogicalOperator;
use muuska\constants\operator\Operator;
use muuska\dao\constants\DataChangeCode;
use muuska\dao\constants\SortDirection;
use muuska\util\App;

abstract class AbstractDAO implements DAO{
	
    /**
     * @var DAOSource
     */
    protected $source;
	
    /**
     * @var DAOFactory
     */
    protected $daoFactory;
    
    /**
     * @var \muuska\model\ModelDefinition
     */
    protected $definition;
    
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, DAOFactory $daoFactory, DAOSource $source){
		$this->daoFactory= $daoFactory;
        $this->source= $source;
        $this->definition = $modelDefinition;
    }
    
    /**
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @param boolean $update
     */
    protected function validation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null, $update = false){
        $input = App::validations()->createModelValidationInput($model, $this->getLang($saveConfig), $this, $saveConfig, $update);
        $validationResult = $this->definition->validateModel($input);
        if (!$validationResult->isValid()) {
            throw App::daos()->createInvalidModelException($validationResult, 'Some fields are invalid.');
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::getModelDefinition()
     */
    public function getModelDefinition(){
        return $this->definition;
    }
	
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::save()
     */
    public function save(object $model, \muuska\dao\util\SaveConfig $saveConfig = null) {
	    $result = $this->definition->isUpdateRequired($model, $saveConfig) ? $this->update($model, $saveConfig) : $this->add($model, $saveConfig);
		return $result;
	}
	
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::add()
     */
    public function add(object $model, \muuska\dao\util\SaveConfig $saveConfig = null) {
	    $this->validation($model, $saveConfig);
	    $result = true;
	    $dataChangeEvent = App::daos()->createDataChangeEvent($this, DataChangeCode::ADDED, $saveConfig, $model);
		$dataAddEvent = App::daos()->createModelAddEvent($this, $model, $saveConfig);
		if(App::getEventTrigger()->fireDAOEvent('before', $dataChangeEvent) && App::getEventTrigger()->fireDAOEvent('before', $dataAddEvent)){
		    if(($saveConfig !== null) && $saveConfig->hasAssociatedFieldsSaveConfig()){
		        $result = $this->saveAssociatedFields($model, $saveConfig, false);
		    }
		    if($result){
		        $creationDateField = $this->definition->getCreationDateField();
		        if (!empty($creationDateField) && $this->definition->containsField($creationDateField)) {
		            $this->definition->setPropertyValue($model, $creationDateField, date('Y-m-d H:i:s'));
		        }
		        $lastModifiedDateField = $this->definition->getLastModifiedDateField();
		        if (!empty($lastModifiedDateField) && $this->definition->containsField($lastModifiedDateField)) {
		            $this->definition->setPropertyValue($model, $lastModifiedDateField, date('Y-m-d H:i:s'));
		        }
		        if ($this->definition->isPositionFieldNeedsAutoValue()) {
		            $this->setModelNewPosition($model);
		        }
		        $languages = $this->getLanguages($saveConfig);
		        $this->definition->formatFields($model, $this, $languages, $this->getLang($saveConfig));
		        $result = $this->addImplementation($model);
		    }
		    if(($saveConfig !== null) && $saveConfig->hasMultipleSaveAssociations()){
		        $this->saveMultipleAssociateds($model, $saveConfig, false);
		    }
		    if($result){
		        App::getEventTrigger()->fireDAOEvent('after', $dataAddEvent->createAfterEvent());
		        App::getEventTrigger()->fireDAOEvent('after', $dataChangeEvent->createAfterEvent());
		    }
		}
		
        return $result;
    }
    
    /**
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @return bool
     */
    protected  function saveAssociatedFields(object $model, \muuska\dao\util\SaveConfig $saveConfig, $update = false) {
        $result = true;
        $associatedFieldsSaveConfig = $saveConfig->getAssociatedFieldsSaveConfig();
        foreach ($associatedFieldsSaveConfig as $associatedField => $associatedFieldSaveConfig) {
            $associated = $this->definition->getAssociatedModel($model, $associatedField);
            if($associated !== null){
                if($this->getForeignDAO($associatedField)->save($associated, $associatedFieldSaveConfig)){
                    $this->definition->setPropertyValue($model, $associatedField, $this->definition->getAssociationDefinition($associatedField)->getPrimaryValue($associated));
                }else{
                    $result = false;
                }
            }
        }
        return $result;
    }
    
    /**
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @return bool
     */
    protected  function saveMultipleAssociateds(object $model, \muuska\dao\util\SaveConfig $saveConfig, $update = false) {
        $result = true;
        $multipleSaveAssociations = $saveConfig->getMultipleSaveAssociations();
        $currentId = $this->definition->getPrimaryValue($model);
        foreach ($multipleSaveAssociations as $associationName => $multipleSaveAssociation) {
            /**
             * @var \muuska\dao\util\MultipleSaveAssociation $multipleSaveAssociation
             */
            $associationName = $multipleSaveAssociation->getAssociationName();
            $externalField = $this->definition->getMultipleAssociationField($associationName);
            $models = $this->definition->getMultipleAssociatedModels($model, $associationName);
            $dao = $this->getMultipleAssociationDAO($associationName);
            if($dao !== null){
                if($update){
                    if($dao->getModelDefinition()->hasMultiplePrimary()){
                        $deleteConfig = $this->createDeleteConfig();
                        $deleteConfig->addRestrictionFieldFromParams($externalField, $currentId);
                        $dao->deleteMultipleRows($deleteConfig);
                        foreach ($models as $key => $tmpModel) {
                            $dao->getModelDefinition()->setPropertyValue($tmpModel, $externalField, $currentId);
                            $result = $result && $dao->add($tmpModel, $multipleSaveAssociation->getModelSpecificSaveConfig($key));
                        }
                    }else{
                        $newIds = array();
                        foreach ($models as $key => $tmpModel) {
                            $dao->getModelDefinition()->setPropertyValue($tmpModel, $externalField, $currentId);
                            $tmpResult = $dao->save($tmpModel, $multipleSaveAssociation->getModelSpecificSaveConfig($key));
                            $result = $result && $tmpResult;
                            if($tmpResult){
                                $newIds[] = $dao->getModelDefinition()->getPrimaryValue($tmpModel);
                            }
                        }
                        $deleteConfig = $this->createDeleteConfig();
                        $deleteConfig->addRestrictionFieldFromParams($externalField, $currentId);
                        $deleteConfig->addRestrictionFieldFromParams($dao->getModelDefinition()->getPrimary(), $newIds, Operator::NOT_IN_LIST);
                        $dao->deleteMultipleRows($deleteConfig);
                    }
                }else{
                    foreach ($models as $key => $tmpModel) {
                        $dao->getModelDefinition()->setPropertyValue($tmpModel, $externalField, $currentId);
                        $result = $result && $dao->add($tmpModel, $multipleSaveAssociation->getModelSpecificSaveConfig($key));
                    }
                }
            }
        }
        
        return $result;
    }
	
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::update()
     */
    public function update(object $model, \muuska\dao\util\SaveConfig $saveConfig = null) {
        $this->validation($model, $saveConfig, true);
		$result = true;
		$dataChangeEvent = App::daos()->createDataChangeEvent($this, DataChangeCode::UPDATED, $saveConfig, $model);
		$dataUpdateEvent = App::daos()->createModelUpdateEvent($this, $model, $saveConfig);
		if(App::getEventTrigger()->fireDAOEvent('before', $dataChangeEvent) && App::getEventTrigger()->fireDAOEvent('before', $dataUpdateEvent)){
		    if(($saveConfig != null) && $saveConfig->hasAssociatedFieldsSaveConfig()){
		        $result = $this->saveAssociatedFields($model, $saveConfig, true);
		    }
		    if($result){
		        $lastModifiedDateField = $this->definition->getLastModifiedDateField();
		        if (!empty($lastModifiedDateField) && $this->definition->containsField($lastModifiedDateField)) {
		            $this->definition->setPropertyValue($model, $lastModifiedDateField, date('Y-m-d H:i:s'));
		        }
		        $languages = $this->getLanguages($saveConfig);
		        $this->definition->formatFields($model, $this, $languages, $this->getLang($saveConfig));
		        
		        $result = $this->updateImplementation($model, $saveConfig);
		    }
		    if(($saveConfig !== null) && $saveConfig->hasMultipleSaveAssociations()){
		        $this->saveMultipleAssociateds($model, $saveConfig, true);
		    }
		    if($result){
		        App::getEventTrigger()->fireDAOEvent('after', $dataUpdateEvent->createAfterEvent());
		        App::getEventTrigger()->fireDAOEvent('after', $dataChangeEvent->createAfterEvent());
		    }
		}
		
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::updateMultipleRows()
     */
    public function updateMultipleRows(object $model, \muuska\dao\util\SaveConfig $saveConfig = null) {
        $this->validation($model, $saveConfig, true);
        $result = true;
        $dataChangeEvent = App::daos()->createDataChangeEvent($this, DataChangeCode::MULTIPLE_ROWS_UPDATED, $saveConfig, $model);
        $dataUpdateEvent = App::daos()->createMultipleRowsUpdateEvent($this, $model, $saveConfig);
        if(App::getEventTrigger()->fireDAOEvent('before', $dataChangeEvent) && App::getEventTrigger()->fireDAOEvent('before', $dataUpdateEvent)){
            $lastModifiedDateField = $this->definition->getLastModifiedDateField();
            if (!empty($lastModifiedDateField) && $this->definition->containsField($lastModifiedDateField)) {
                $this->definition->setPropertyValue($model, $lastModifiedDateField, date('Y-m-d H:i:s'));
            }
            $languages = $this->getLanguages($saveConfig);
            $this->definition->formatFields($model, $this, $languages, $this->getLang($saveConfig));
            
            $result = $this->updateMultipleRowsImplementation($model, $saveConfig);
            if($result){
                App::getEventTrigger()->fireDAOEvent('after', $dataUpdateEvent->createAfterEvent());
                App::getEventTrigger()->fireDAOEvent('after', $dataChangeEvent->createAfterEvent());
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::updateMultipleRowsFromArray()
     */
    public function updateMultipleRowsFromArray($data, \muuska\dao\util\SaveConfig $saveConfig = null) {
        $result = true;
        
        $model = $this->createModel();
        $fields = array();
        foreach ($data as $field => $value) {
            $fields[] = $field;
            if($this->definition->isLangField($field) && is_array($value)){
                $this->definition->setAllLangsPropertyValues($model, $field, $value);
            }else{
                $this->definition->setPropertyValue($model, $field, $value);
            }
        }
        if ($saveConfig === null) {
            $saveConfig = $this->createSaveConfig();
        }
        if(!empty($fields)){
            $saveConfig->setSpecificFields($fields);
            $result = $this->updateMultipleRows($model, $saveConfig);
        }
        
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::activate()
     */
    public function activate(object $model) {
        return $this->changeActive($model, 1);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::deactivate()
     */
    public function deactivate(object $model) {
        return $this->changeActive($model, 0);
    }
    
    /**
     * Update object active value
     *
     * @param object $model
     * @param bool $active
     * @return bool
     */
    protected function changeActive(object $model, $active) {
        $result = $this->changeValue($model, 'active', (int)$active);
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::changeValue()
     */
    public function changeValue(object $model, $field, $value) {
        if($this->definition->containsField($field)){
            if($this->definition->isLangField($field) && is_array($value)){
                $this->definition->setAllLangsPropertyValues($model, $field, $value);
            }else{
                $this->definition->setPropertyValue($model, $field, $value);
            }
            $saveConfig = $this->createSaveConfig();
            $saveConfig->addSpecificField($field);
            $result = $this->update($model, $saveConfig);
            return $result;
        }else{
            throw new \Exception('Model must contain field ' . $field);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::changeValueOnMultipleRows()
     */
    public function changeValueOnMultipleRows($field, $value, \muuska\dao\util\SaveConfig $saveConfig = null) {
        $this->updateMultipleRowsFromArray(array($field => $value), $saveConfig);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::delete()
     */
    public function delete(object $model, \muuska\dao\util\DeleteConfig $deleteConfig = null) {
        $result = true;
        $dataChangeEvent = App::daos()->createDataChangeEvent($this, DataChangeCode::DELETED, $deleteConfig, $model);
        $dataDeleteEvent = App::daos()->createModelDeleteEvent($this, $model, $deleteConfig);
        if(App::getEventTrigger()->fireDAOEvent('before', $dataChangeEvent) && App::getEventTrigger()->fireDAOEvent('before', $dataDeleteEvent)){
            $virtual = ($deleteConfig !== null) ? $deleteConfig->isVirtual() : true;
            $deleteField = $this->definition->getVirtualDeletionField();
            if($virtual && !empty($deleteField) && $this->definition->containsField($deleteField)){
                $result = $this->changeValue($model, $deleteField, 1);
            }else{
                $result = $this->deleteImplementation($model, $deleteConfig);
            }
            if($result){
                App::getEventTrigger()->fireDAOEvent('after', $dataDeleteEvent->createAfterEvent());
                App::getEventTrigger()->fireDAOEvent('after', $dataChangeEvent->createAfterEvent());
            }
        }
        
		return $result;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::deleteMultipleRows()
     */
    public function deleteMultipleRows(\muuska\dao\util\DeleteConfig $deleteConfig = null) {
        $result = true;
        $dataChangeEvent = App::daos()->createDataChangeEvent($this, DataChangeCode::MULTIPLE_ROWS_DELETED, $deleteConfig);
        $dataDeleteEvent = App::daos()->createMultipleRowsDeleteEvent($this, $deleteConfig);
        if(App::getEventTrigger()->fireDAOEvent('before', $dataChangeEvent) && App::getEventTrigger()->fireDAOEvent('before', $dataDeleteEvent)){
            $virtual = ($deleteConfig !== null) ? $deleteConfig->isVirtual() : true;
            $deleteField = $this->definition->getVirtualDeletionField();
            if($virtual && !empty($deleteField) && $this->definition->containsField($deleteField)){
                $result = $this->changeValueOnMultipleRows($deleteField, 1, $deleteConfig->createSaveConfig());
            }else{
                $result = $this->deleteMultipleRowsImplementation($deleteConfig);
            }
            if($result){
                App::getEventTrigger()->fireDAOEvent('after', $dataDeleteEvent->createAfterEvent());
                App::getEventTrigger()->fireDAOEvent('after', $dataChangeEvent->createAfterEvent());
            }
        }
        
		return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::getById()
     */
    public function getById($id, \muuska\dao\util\SelectionConfig $selectionConfig = null, $returnEmptyModelIfNotFound = false) {
        if($selectionConfig === null){
            $selectionConfig = $this->createSelectionConfig($this->getLang());
        }
        $selectionConfig->addRestrictionFieldFromParams($this->definition->getPrimary(), $id);
		return $this->getUniqueModel($selectionConfig, $returnEmptyModelIfNotFound);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::getUniqueModel()
     */
    public function getUniqueModel(\muuska\dao\util\SelectionConfig $selectionConfig, $returnEmptyModelIfNotFound = false) {
        if($selectionConfig === null){
            $selectionConfig = $this->createSelectionConfig();
        }
        $selectionConfig->setLimit(1);
        $data = $this->getData($selectionConfig);
        $result = null;
        if(isset($data[0])){
            $result = $data[0];
        }else if($returnEmptyModelIfNotFound){
            $result = $this->createModel();
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::getModelValue()
     */
    public function getModelValue(\muuska\dao\util\SelectionConfig $selectionConfig, $field, $defaultValue = null) {
        if($selectionConfig === null){
            $selectionConfig = $this->createSelectionConfig();
        }
        $selectionConfig->setSpecificFields(array($field));
        if(!$this->definition->isLangField($field)){
            $selectionConfig->setLang(false);
            $selectionConfig->setAllLangsEnabled(false);
        }
        $model = $this->getUniqueModel($selectionConfig, false);
		$value = null;
		if($model !== null){
		    if(($selectionConfig !== null) && $selectionConfig->isAllLangsEnabled() && $this->definition->isLangField($field)){
		        $value = $this->definition->getAllLangsPropertyValues($model, $field);
		    }else{
		        $value = $this->definition->getPropertyValue($model, $field);
		    }
		}else{
		    $value = $defaultValue;
		}
		return $value;
    }
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::getData()
	 */
	public function getData(\muuska\dao\util\SelectionConfig $selectionConfig = null) {
        $selectionConfig = $this->addActiveParam($selectionConfig);
        $this->addDelectedParam($selectionConfig);
        $result = $this->getDataImplementation($selectionConfig);
        if(($selectionConfig !== null) && $selectionConfig->hasMultipleSelectionAssociations()){
            $allIds = $result->getArrayValuesFromField($this->definition->getPrimary());
            $multipleSelectionAssociations = $selectionConfig->getMultipleSelectionAssociations();
            $associationsData = array();
            foreach ($multipleSelectionAssociations as $multipleSelectionAssociation) {
                $associationName = $multipleSelectionAssociation->getAssociationName();
                $associationField = $this->definition->getMultipleAssociationField($associationName);
                $multipleSelectionAssociation->addRestrictionFieldFromParams($associationField, $allIds, Operator::IN_LIST);
                $dao = $this->getMultipleAssociationDAO($associationName);
                if(!$multipleSelectionAssociation->hasLang()){
                    $multipleSelectionAssociation->setLang($selectionConfig->getLang());
                }
                if($dao !== null){
                    $data = $dao->getData($multipleSelectionAssociation);
                    $associationsData[$associationName] = $data->getGroupedArray($associationField);
                }
            }
            $result->setMultipleAssociationsData($associationsData);
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::getDataTotal()
     */
    public function getDataTotal(\muuska\dao\util\SelectionConfig $selectionConfig = null) {
        $selectionConfig = $this->addActiveParam($selectionConfig);
		$this->addDelectedParam($selectionConfig);
		return $this->getDataTotalImplementation($selectionConfig);
    }
	
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::checkUnique()
     */
    public function checkUnique(object $model, $fields, $update = false, \muuska\dao\util\SaveConfig $saveConfig = null) {
		$fields = is_array($fields) ? $fields : array($fields);
		$selectionConfig = $this->createSelectionConfig();
		if($update){
		    $identifierRestriction = $this->createFieldRestriction('identifiers', null);
		    $identifierRestriction->setLogicalOperator(LogicalOperator::OR_);
		    if(($saveConfig !== null) && $saveConfig->hasRestrictions()){
		        $saveRestrictions = $saveConfig->getRestrictionFields();
		        foreach ($saveRestrictions as $key => $saveRestriction) {
		            $newFieldRestriction = clone($saveRestriction);
		            $newFieldRestriction->setOperator(Operator::DIFFERENT);
		            $identifierRestriction->addSubField($newFieldRestriction, $key);
		        }
		    }else{
		        $primaries = $this->definition->getPrimaries();
		        foreach ($primaries as $primary) {
		            $identifierRestriction->addSubField($this->createFieldRestriction($primary, $this->definition->getPropertyValue($model, $primary), Operator::DIFFERENT));
		        }
		    }
		    if($identifierRestriction->hasSubFields()){
		        $selectionConfig->addRestrictionField($identifierRestriction);
		    }
		}
		
		foreach($fields as $field){
			if($this->definition->isLangField($field)){
			    $selectionConfig->setLangEnabled(true);
			    $selectionConfig->setAllLangsEnabled(true);
			    $selectionConfig->addRestrictionFieldFromParams($field, $this->definition->getAllLangsPropertyValues($model, $field), Operator::IN_LIST);
			}else{
			    $selectionConfig->addRestrictionFieldFromParams($field, $this->definition->getPropertyValue($model, $field));
			}
		}
		
		$count = $this->getDataTotal($selectionConfig);
        return ($count==0);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::setModelNewPosition()
     */
    public function setModelNewPosition(object $model) {
        $positionField = $this->definition->getPositionField();
        if(!empty($positionField) && $this->definition->containsField($positionField)){
            $selectionConfig = $this->createSelectionConfig();
            $selectionConfig->setLangEnabled(false);
            $selectionConfig->setSortOptionParams($positionField, SortDirection::DESC);
            $fieldDefinition = $this->definition->getFieldDefinition($positionField);
            if(isset($fieldDefinition['variations'])){
                foreach ($fieldDefinition['variations'] as $field) {
                    $selectionConfig->addRestrictionFieldFromParams($field, $this->definition->getPropertyValue($model, $field));
                }
            }
            $position = $this->getModelValue($selectionConfig, $positionField);
            if($position === null){
                $position = 0;
            }else{
                $position = ((int)$position) + 1;
            }
            $this->definition->setPropertyValue($model, $positionField, $position);
        }
    }
    public function clearData(){
        $result = true;
        $dataChangeEvent = App::daos()->createDataChangeEvent($this, DataChangeCode::CLEARING);
        $dataClearingEvent = App::daos()->createDataClearingEvent($this);
        if(App::getEventTrigger()->fireDAOEvent('before', $dataChangeEvent) && App::getEventTrigger()->fireDAOEvent('before', $dataClearingEvent)){
            $result = $this->clearDataImplementation();
            if($result){
                App::getEventTrigger()->fireDAOEvent('after', $dataClearingEvent->createAfterEvent());
                App::getEventTrigger()->fireDAOEvent('after', $dataChangeEvent->createAfterEvent());
            }
        }
        
        return $result;
    }
    
    /**
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @return \muuska\dao\util\SelectionConfig
     */
    protected function addDelectedParam(\muuska\dao\util\SelectionConfig $selectionConfig = null){
        if(($selectionConfig !== null) && $selectionConfig->isVirtualDeletedEnabled()){
            $deleteField = $this->definition->getVirtualDeletionField();
            if(!empty($deleteField) && $this->definition->containsField($deleteField) && !$selectionConfig->hasRestrictionField($deleteField)){
                $selectionConfig->setRestrictionFieldParams($deleteField, 0);
            }
        }
        return $selectionConfig;
	}
	
	/**
	 * @param \muuska\dao\util\SelectionConfig $selectionConfig
	 * @return \muuska\dao\util\SelectionConfig
	 */
	protected function addActiveParam(\muuska\dao\util\SelectionConfig $selectionConfig = null){
	    if(($selectionConfig !== null) && $selectionConfig->isOnlyActive()){
	        $activeField = $this->definition->getActivationField();
	        if(!empty($activeField) && $this->definition->containsField($activeField) && !$selectionConfig->hasRestrictionField($activeField)){
	            $selectionConfig->setRestrictionFieldParams($activeField, 1);
	        }
	    }
		return $selectionConfig;
	}
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::createModel()
     */
    public function createModel() {
        return $this->definition->createModel();
    }
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::getDefaultLang()
	 */
	public function getDefaultLang()
    {
        return App::getApp()->getDefaultLang();
    }
	
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::getLang()
     */
    public function getLang(\muuska\dao\util\DataConfig $dataConfig = null)
    {
		$lang = '';
		if(($dataConfig === null) || !$dataConfig->hasLang()){
			$lang = $this->getDefaultLang();
		}else{
		    $lang = $dataConfig->getLang();
		}
		return $lang;
    }
	
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::getLanguages()
     */
    public function getLanguages(\muuska\dao\util\SaveConfig $saveConfig = null)
    {
        $saveConfigLanguages = ($saveConfig === null) ? array() : $saveConfig->getLanguages();
		if(empty($saveConfigLanguages)){
		    $saveConfigLanguages = App::getApp()->getLanguages();
		}
		return $saveConfigLanguages;
    }
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::getForeignDAO()
	 */
	public function getForeignDAO($field)
    {
		$dao = null;
		$modelDefinition = $this->definition->getAssociationDefinition($field);
		if($modelDefinition !== null){
		    $dao = $this->daoFactory->getDAO($modelDefinition);
        }else{
            throw new \Exception('Field "' . $field.'" doest not have any reference');
        }
		return $dao;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::getMultipleAssociationDAO()
     */
    public function getMultipleAssociationDAO($associationName){
        return $this->daoFactory->getDAO($this->definition->getMultipleAssociationDefinition($associationName));
    }
	
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAO::loadAssociatedObject()
     */
    public function loadAssociatedObject(object $model, $field, \muuska\dao\util\SelectionConfig $selectionConfig = null, $returnEmptyModelIfNotFound = false){
        $result = $this->getForeignDAO($field)->getById($this->definition->getPropertyValue($model, $field), $selectionConfig, $returnEmptyModelIfNotFound);
	    $this->definition->setAssociatedModel($model, $field, $result);
	    return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::loadMultipleAssociatedObjects()
	 */
	public function loadMultipleAssociatedObjects(object $model, $associationName, \muuska\dao\util\SelectionConfig $selectionConfig = null){
	    $result = null;
	    if($selectionConfig === null){
	        $selectionConfig = $this->createSelectionConfig();
	    }
	    $selectionConfig->addRestrictionFieldFromParams($this->definition->getMultipleAssociationField($associationName), $this->definition->getPrimaryValue($model));
	    $multipleDao = $this->getMultipleAssociationDAO($associationName);
	    $result = $multipleDao->getData($selectionConfig);
	    $this->definition->setMultipleAssociatedModels($model, $associationName, $result->toArray());
	    return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::getParents()
	 */
	public function getParents(object $model, \muuska\dao\util\SelectionConfig $selectionConfig = null){
	    $result = App::models()->createModelCollection($this->definition);
	    $parentId = $this->definition->getPropertyValue($model, $this->definition->getParentField());
	    if(!empty($parentId)){
	        $parent = $this->getById($parentId, $selectionConfig);
	        if($parent !== null){
	            $result->add($parent);
	            $result->addCollection($this->getParents($parent, $selectionConfig));
	        }
	    }
	    return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::getChildren()
	 */
	public function getChildren(object $model, \muuska\dao\util\SelectionConfig $selectionConfig = null){
	    if($selectionConfig === null){
	        $selectionConfig = $this->createSelectionConfig();
	    }
	    $selectionConfig->addRestrictionFieldFromParams($this->definition->getParentField(), $this->definition->getPrimaryValue($model));
	    $result = $this->getData($selectionConfig);
	    return $result;
	}
	
	/**
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @return boolean
	 */
	protected function isSaveConfigLangEnabled(\muuska\dao\util\SaveConfig $saveConfig = null){
	    return (($saveConfig === null) || $saveConfig->isLangEnabled());
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::protectString()
	 */
	public function protectString($string, $html_ok = false){
	    return $this->source->protectString($string, $html_ok);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::createFieldRestriction()
	 */
	public function createFieldRestriction($fieldName, $value, $operator = null){
		return App::daos()->createFieldRestriction($fieldName, $value, $operator);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::createSaveConfig()
	 */
	public function createSaveConfig($lang = null, $languages = array()){
	    if(empty($lang)){
	        $lang = $this->getDefaultLang();
	    }
	    return App::daos()->createSaveConfig($lang, $languages);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::createSelectionAssociation()
	 */
	public function createSelectionAssociation($fieldName, $langEnabled = true, $allLangsEnabled = false, $joinType = null, $retrievingEnabled = true){
	    return App::daos()->createSelectionAssociation($fieldName, $langEnabled, $allLangsEnabled, $joinType, $retrievingEnabled);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::createSelectionConfig()
	 */
	public function createSelectionConfig($lang = ''){
	    if(empty($lang)){
	        $lang = $this->getDefaultLang();
	    }
	    return App::daos()->createSelectionConfig($lang);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::createDeleteConfig()
	 */
	public function createDeleteConfig($virtual = true){
	    return App::daos()->createDeleteConfig($virtual);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\DAO::getProject()
	 */
	public function getProject() {
	    return $this->definition->getProject();
	}
	
	/**
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @return array
	 */
	protected function getFinalSaveFields(\muuska\dao\util\SaveConfig $saveConfig = null)
	{
	    $result = $this->definition->getFields();
	    if($saveConfig !== null){
	        $result = $saveConfig->getFinalFields($result);
	    }
	    return $result;
	}
	
	protected function formatSaveConfigForUpdate(object $model, \muuska\dao\util\SaveConfig $saveConfig = null) {
	    if(!$this->definition->hasMultiplePrimary()){
	        if ($saveConfig === null) {
	            $saveConfig = $this->createSaveConfig();
	        }
	        $primary = $this->definition->getPrimary();
	        if($this->definition->isAutoIncrement() || !$saveConfig->hasRestrictionField($primary)){
	            $saveConfig->addRestrictionFieldFromParams($primary, $this->definition->getPrimaryValue($model));
	        }
	    }else{
	        if ($saveConfig === null) {
	            $saveConfig = $this->createSaveConfig();
	        }
	        if(!$saveConfig->hasRestrictionForFields($this->definition->getPrimaries())){
	            $saveConfig->createRestrictionFieldsFromArray($this->definition->getPrimaryValues($model));
	        }
	    }
	    return $saveConfig;
	}
	
	/**
	 * @param object $model
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @return bool
	 */
	protected abstract function addImplementation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null);
	
	/**
	 * @param object $model
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @return bool
	 */
	protected abstract function updateImplementation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null);
	
	/**
	 * @param object $model
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @return bool
	 */
	protected abstract function updateMultipleRowsImplementation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null);
	
	/**
	 * @param object $model
	 * @param \muuska\dao\util\DeleteConfig $deleteConfig
	 * @return bool
	 */
	protected abstract function deleteImplementation(object $model, \muuska\dao\util\DeleteConfig $deleteConfig = null);
	
	/**
	 * @param \muuska\dao\util\DeleteConfig $deleteConfig
	 * @return bool
	 */
	protected abstract function deleteMultipleRowsImplementation(\muuska\dao\util\DeleteConfig $deleteConfig = null);
	
	/**
	 * @param \muuska\dao\util\SelectionConfig $selectionConfig
	 * @return \muuska\dao\util\DAOListResult
	 */
	protected abstract function getDataImplementation(\muuska\dao\util\SelectionConfig $selectionConfig = null);
	
	/**
	 * @param \muuska\dao\util\SelectionConfig $selectionConfig
	 * @return int
	 */
	protected abstract function getDataTotalImplementation(\muuska\dao\util\SelectionConfig $selectionConfig = null); 
	
	/**
	 * @return bool
	 */
	protected abstract function clearDataImplementation(); 
}
