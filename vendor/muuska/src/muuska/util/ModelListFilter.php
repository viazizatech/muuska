<?php
namespace muuska\util;

use muuska\constants\operator\LogicalOperator;
use muuska\dao\constants\SortDirection;

class ModelListFilter
{
	/**
	 * @var array
	 */
	protected $data;
	
	/**
	 * @var \muuska\dao\DAO
	 */
	protected $dao;

	/**
	 * @param array $data
	 * @param \muuska\dao\DAO $dao
	 */
	public function __construct($data, \muuska\dao\DAO $dao) {
        $this->data = $data;
        $this->dao = $dao;
    }
    
    /**
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @return \muuska\dao\util\DAOListResult
     */
    public function getData(\muuska\dao\util\SelectionConfig $selectionConfig = null) {
        $finalData = $this->data;
        $totalWithoutLimit = 0;
        if($selectionConfig !== null){
            $finalData = $this->filterData($selectionConfig);
            $finalData = $this->sortData($finalData, $selectionConfig);
            if($selectionConfig->isDataCountingEnabled()){
                $totalWithoutLimit = count($finalData);
            }
            if($selectionConfig->hasLimit()){
                $finalData = array_slice($finalData, $selectionConfig->getStart(), $selectionConfig->getLimit());
            }
        }
        return App::daos()->createDAOListResult($this->dao->getModelDefinition(), $finalData, $totalWithoutLimit);
    }
    
    /**
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @return int
     */
    public function getDataTotal(\muuska\dao\util\SelectionConfig $selectionConfig = null) {
        if($selectionConfig === null){
            $selectionConfig = $this->dao->createSelectionConfig();
        }
        $selectionConfig->setDataCountingEnabled(true);
        return $this->getData($selectionConfig)->getTotalWithoutLimit();
    }
    
    /**
     * @param \muuska\dao\util\DataConfig $dataConfig
     * @return array
     */
    public function filterData(\muuska\dao\util\DataConfig $dataConfig = null){
        $result = $this->data;
        if(($dataConfig !== null) && $dataConfig->hasRestrictions()){
            $result = array();
            $logicalOperator = $dataConfig->getLogicalOperator();
            $restrictionFields =  $dataConfig->getRestrictionFields();
            foreach ($this->data as $model) {
                if($this->checkRestrictions($model, $restrictionFields, $logicalOperator)){
                    $result[] = $model;
                }
            }
        }
        return $result;
    }
    
    /**
     * @param object $model
     * @param \muuska\dao\util\FieldRestriction[] $restrictionFields
     * @param int $logicalOperator
     * @return boolean
     */
    protected function checkRestrictions(object $model, $restrictionFields, $logicalOperator){
        $result = true;
        foreach ($restrictionFields as $restrictionField) {
            $result = $this->checkRestriction($model, $restrictionField);
            if($logicalOperator == LogicalOperator::OR_){
                if($result){
                    break;
                }
            }elseif(!$result){
                break;
            }
        }
        return $result;
    }
    
    /**
     * @param object $model
     * @param \muuska\dao\util\FieldRestriction $restrictionField
     * @return boolean
     */
    protected function checkRestriction(object $model, \muuska\dao\util\FieldRestriction $restrictionField){
        $result = true;
        if($restrictionField->hasSubFields()){
            $result = $this->checkRestrictions($model, $restrictionField->getSubFields(), $restrictionField->getLogicalOperator());
        }else{
            if(!$restrictionField->isFieldValueType() && !$restrictionField->isDaoFunctionValueType() && !$restrictionField->isForeign()){
                $modelValue = $this->dao->getModelDefinition()->getPropertyValue($model, $restrictionField->getFieldName());
                $result = App::getTools()->checkValue($modelValue, $restrictionField->getValue(), $restrictionField->getOperator(), false);
            }
        }
        return $result;
    }
    
    /**
     * @param array $data
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @return array
     */
    protected function sortData($data, \muuska\dao\util\SelectionConfig $selectionConfig){
        if($selectionConfig->hasSortOptions()){
            $sortOptions = $selectionConfig->getSortOptions();
            foreach ($sortOptions as $sortOption) {
                $sorter = new DataSorter($data, $this->dao->getModelDefinition(), $sortOption);
                $data = $sorter->sortList();
            }
        }
        return $data;
    }
    
    /**
     * @param \muuska\dao\util\DeleteConfig $deleteConfig
     * @return array
     */
    public function removeData(\muuska\dao\util\DeleteConfig $deleteConfig = null){
        $result = array();
        if(($deleteConfig !== null) && $deleteConfig->hasRestrictions()){
            $result = array();
            $logicalOperator = $deleteConfig->getLogicalOperator();
            $restrictionFields =  $deleteConfig->getRestrictionFields();
            foreach ($this->data as $model) {
                if(!$this->checkRestrictions($model, $restrictionFields, $logicalOperator)){
                    $result[] = $model;
                }
            }
        }
        return $result;
    }
    
    /**
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @return array
     */
    public function updateData(object $model, \muuska\dao\util\SaveConfig $saveConfig = null){
        $data = $this->filterData($saveConfig);
        $fields = $this->getFinalSaveFields($saveConfig);
        
        $simpleFields = array();
        $langFields = array();
        $definition = $this->dao->getModelDefinition();
        foreach ($fields as $field) {
            if($definition->isLangField($field)){
                $langFields[] = $field;
            }else{
                $simpleFields[] = $field;
            }
        }
        $definition = $this->dao->getModelDefinition();
        foreach ($data as $oldModel) {
            foreach ($simpleFields as $field) {
                $definition->setPropertyValue($oldModel, $field, $definition->getPropertyValue($model, $field));
            }
            if($definition->isMultilingual()){
                foreach ($langFields as $field) {
                    $definition->setAllLangsPropertyValues($oldModel, $field, $definition->getAllLangsPropertyValues($model, $field));
                }
            }
        }
        return $this->data;
    }
    
    /**
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @return string[]
     */
    protected function getFinalSaveFields(\muuska\dao\util\SaveConfig $saveConfig = null)
    {
        $result = $this->dao->getModelDefinition()->getFields();
        if($saveConfig !== null){
            $result = $saveConfig->getFinalFields($result);
        }
        return $result;
    }
}

class DataSorter
{
    /**
     * @var array
     */
    protected $data;
    
    /**
     * @var \muuska\model\ModelDefinition
     */
    protected $modelDefinition;
    
    /**
     * @var \muuska\dao\util\SortOption
     */
    protected $sortOption;
    
    /**
     * @param array $data
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param \muuska\dao\util\SortOption $sortOption
     */
    public function __construct($data, \muuska\model\ModelDefinition $modelDefinition, \muuska\dao\util\SortOption $sortOption){
        $this->data = $data;
        $this->modelDefinition = $modelDefinition;
        $this->sortOption = $sortOption;
    }
    
    /**
     * @return array
     */
    public function sortList(){
        $list = $this->data;
        usort($list, [$this,'compare']);
        return $list;
    }
    
    /**
     * @param object $item1
     * @param object $item2
     * @return int
     */
    public function compare($item1, $item2){
        $value1 = $this->getValue($item1);
        $value2 = $this->getValue($item2);
        if($value1==$value2){
            return 0;
        }else if($this->sortOption->getDirection()==SortDirection::DESC){
            return ($value1 < $value2) ? 1 : -1;
        }else{
            return ($value1 < $value2) ? -1 : 1;
        }
    }
    
    /**
     * @param object $model
     * @return mixed
     */
    public function getValue($model){
        return $this->modelDefinition->getPropertyValue($model, $this->sortOption->getFieldName());
    }
}
