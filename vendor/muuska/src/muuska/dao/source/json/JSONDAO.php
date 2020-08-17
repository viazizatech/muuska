<?php
namespace muuska\dao\source\json;
use muuska\dao\AbstractDAO;
use muuska\util\App;
use muuska\constants\FolderPath;

class JSONDAO extends AbstractDAO{
    /**
     * @var string
     */
    protected $fileName;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\dao\DAOSource $source
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, \muuska\dao\DAOFactory $daoFactory, \muuska\dao\DAOSource $source){
        parent::__construct($modelDefinition, $daoFactory, $source);
        $this->fileName = App::getApp()->getStorageDir() . FolderPath::DAO . '/' . $source->getName() . '/'.$this->definition->getFullName().'.json';
    }
    
    protected function addImplementation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null)
    {
        $fields = $this->getFinalSaveFields($saveConfig);
        $newModel = $this->createModel();
        foreach ($fields as $field) {
            if($this->definition->isLangField($field)){
                $this->definition->setAllLangsPropertyValues($newModel, $field, $this->definition->getAllLangsPropertyValues($model, $field));
            }else{
                $this->definition->setPropertyValue($newModel, $field, $this->definition->getPropertyValue($model, $field));
            }
        }
        if($this->definition->isAutoIncrement()){
            $newId = $this->getNewId(true);
            $this->definition->setPrimaryValue($model, $newId);
            $this->definition->setPrimaryValue($newModel, $newId);
        }
        $models = $this->getModelsFromFile($saveConfig, true);
        $models[] = $newModel;
        return $this->saveModels($models);
    }
    protected function getNewId($autoSave = true) {
        $file = App::getApp()->getStorageDir() . FolderPath::DAO .'/'. $this->source->getName() . '/auto_ids.json';
        $name = $this->definition->getFullName();
        $array = App::getFileTools()->getArrayFromJsonFile($file);
        if(!isset($array[$name])){
            $array[$name] = 0;
        }
        $array[$name] += 1;
        if($autoSave){
            App::getFileTools()->filePutContents($file, json_encode($array));
        }
        return $array[$name];
    }
    protected function updateImplementation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null) {
        return $this->doUpdate($model, $this->formatSaveConfigForUpdate($model, $saveConfig), false);
    }
    
    protected function updateMultipleRowsImplementation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null){
        return $this->doUpdate($model, $saveConfig, true);
    }
    protected function doUpdate(object $model, \muuska\dao\util\SaveConfig $saveConfig = null, $isMultipleUpdate = false){
        return $this->saveModels($this->getModelFilter($saveConfig, true)->updateData($model, $saveConfig));
    }
    protected function deleteImplementation(object $model, \muuska\dao\util\DeleteConfig $deleteConfig = null) {
        if($deleteConfig === null){
            $deleteConfig = $this->createDeleteConfig();
        }
        $deleteConfig->createRestrictionFieldsFromArray($this->definition->getPrimaryValues($model));
        return $this->deleteMultipleRowsImplementation($deleteConfig);
    }
    protected function deleteMultipleRowsImplementation(\muuska\dao\util\DeleteConfig $deleteConfig = null){
        return $this->saveModels($this->getModelFilter($deleteConfig, false)->removeData($deleteConfig));
    }
    
    protected function getDataImplementation(\muuska\dao\util\SelectionConfig $selectionConfig = null)
    {
        $allLangsEnabled = ($selectionConfig !== null) ? $selectionConfig->isAllLangsEnabled() : false;
        return $this->getModelFilter($selectionConfig, $allLangsEnabled)->getData($selectionConfig);
    }
    protected function getDataTotalImplementation(\muuska\dao\util\SelectionConfig $selectionConfig = null)
    {
        return $this->getModelFilter($selectionConfig, false)->getDataTotal($selectionConfig);
    }
    protected function getModelFilter(\muuska\dao\util\DataConfig $dataConfig = null, $allLangsEnabled = false)
    {
        return App::utils()->createModelListFilter($this->getModelsFromFile($dataConfig, $allLangsEnabled), $this);
    }
    protected function getModelsFromFile(\muuska\dao\util\DataConfig $dataConfig = null, $allLangsEnabled = false)
    {
        $result = array();
        $defaultLang = $this->getLang($dataConfig);
        $array = App::getFileTools()->getArrayFromJsonFile($this->fileName);
        $multilingual = $this->definition->isMultilingual();
        $allLangsEnabled = $multilingual ? $allLangsEnabled : false;
        foreach ($array as $row) {
            $model = $this->definition->createModel();
            if($multilingual){
                if(isset($row['simpleFields'])){
                    $this->setModelSimpleFields($model, $row['simpleFields']);
                }
                if(isset($row['langFields'])){
                    $this->setModelLangFields($model, $row['langFields'], $defaultLang, $allLangsEnabled);
                }
            }else{
                $this->setModelSimpleFields($model, $row);
            }
            $result[] = $model;
        }
        return $result;
    }
    
    protected function setModelSimpleFields(object $model, $data)
    {
        if(is_array($data)){
            foreach ($data as $field => $value) {
                $this->definition->setPropertyValue($model, $field, $value);
            }
        }
    }
    protected function setModelLangFields(object $model, $data, $defaultLang, $allLangsEnabled)
    {
        if(is_array($data)){
            foreach ($data as $field => $arrayValues) {
                if(is_array($arrayValues)){
                    if(isset($arrayValues[$defaultLang])){
                        $this->definition->setPropertyValue($model, $field, $arrayValues[$defaultLang]);
                    }
                    if($allLangsEnabled){
                        $this->definition->setAllLangsPropertyValues($model, $field, $arrayValues);
                    }
                }
            }
        }
    }
    protected function saveModels($models)
    {
        $array = array();
        $multilingual = $this->definition->isMultilingual();
        $simpleFields = $this->definition->getSimpleFields();
        $langFields = $this->definition->getLangFields();
        foreach ($models as $model) {
            $row = array();
            if($multilingual){
                if(!$this->definition->hasMultiplePrimary()){
                    $row['simpleFields'][$this->definition->getPrimary()] = $this->definition->getPrimaryValue($model);
                }
                foreach ($simpleFields as $field) {
                    $row['simpleFields'][$field] = $this->definition->getPropertyValue($model, $field);
                }
                foreach ($langFields as $field) {
                    $row['langFields'][$field] = $this->definition->getAllLangsPropertyValues($model, $field);
                }
            }else{
                if(!$this->definition->hasMultiplePrimary()){
                    $row[$this->definition->getPrimary()] = $this->definition->getPrimaryValue($model);
                }
                foreach ($simpleFields as $field) {
                    $row[$field] = $this->definition->getPropertyValue($model, $field);
                }
            }
            $array[] = $row;
        }
        return App::getFileTools()->filePutContents($this->fileName, json_encode($array, JSON_PRETTY_PRINT));
    }
    protected function clearDataImplementation()
    {
        return file_exists($this->fileName) ? unlink($this->fileName) : true;
    }
}