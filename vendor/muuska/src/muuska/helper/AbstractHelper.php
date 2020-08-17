<?php
namespace muuska\helper;

use muuska\constants\DataType;
use muuska\constants\ExternalFieldEditionType;
use muuska\constants\FieldNature;
use muuska\util\App;

class AbstractHelper
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var \muuska\controller\ControllerInput
     */
    protected $input;
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     */
    public function __construct(\muuska\controller\ControllerInput $controllerInput){
        $this->input = $controllerInput;
    }
    
    /**
     * @var array
     */
    protected $errors;
    
    /**
     * @param string $string
     * @return string
     */
    public function l($string, $context = '') {
        return App::translateFramework(App::translations()->createHelperTranslationConfig($this->name), $string, $this->input->getLang(), $context);
    }
    
    /**
     * @param string $action
     * @return boolean
     */
    public function isActionEnabled($action)
    {
        return (!$this->input->getSubApplication()->getConfig()->getBool('access_checking_required', false) || $this->input->getCurrentUser()->checkAccess($this->input->getControllerResourceTree(App::securities()->createResourceTree($action))));
    }
    
    /**
     * @param \muuska\model\ModelDefinition $definition
     * @param string $string
     * @param string $context
     * @return string
     */
    public function translateModel(\muuska\model\ModelDefinition $definition, $string, $context = '') {
        $project = $definition->getProject();
        $config = App::translations()->createModelTranslationConfig($definition);
        $baseTranslator = $project->getTranslator($config);
        $translator = $this->input->getSubProject()->getTranslator($config, $baseTranslator);
        return ($translator !== null) ? $translator->translate($this->input->getLang(), $string, $context) : $string;
    }
    
    public function hasErrors(){
        return !empty($this->errors);
    }
    
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }
    
    public function createFieldValueRenderer($field, $fieldDefinition, \muuska\dao\DAO $dao, \muuska\getter\Getter $valueGetter, \muuska\getter\Getter $finalModelGetter = null)
    {
        $renderer = null;
        $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
        $type = isset($fieldDefinition['type']) ? $fieldDefinition['type'] : '';
        if($type==DataType::TYPE_BOOL){
            $renderer = App::renderers()->createOptionLabelRenderer(App::options()->createBoolProvider($this->input->getLang()), $valueGetter);
        }elseif(($nature == FieldNature::OBJECT_STATE) || ($nature == FieldNature::OPTION)){
            if(isset($fieldDefinition['optionProvider'])){
                $renderer = App::renderers()->createOptionLabelRenderer($fieldDefinition['optionProvider']->getLangOptionProvider($this->input->getLang()), $valueGetter);
            }
        }elseif(($nature == FieldNature::FILE) || ($nature == FieldNature::IMAGE)){
            $renderer = App::renderers()->createModelFileRenderer($dao->getModelDefinition(), $field, $finalModelGetter);
        }else{
            $renderer = App::renderers()->createSimpleValueRenderer($valueGetter);
        }
        return $renderer;
    }
    
    
    /**
     * @param string $name
     * @return \muuska\renderer\template\Template
     */
    public function getRendererFromName($name){
        $renderer = null;
        $theme = $this->input->getTheme();
        if($theme !== null){
            $renderer = $theme->createTemplate($name);
        }
        return $renderer;
    }
    
    public function retrieveModelData($update, \muuska\dao\util\SaveConfig $saveConfig, \muuska\dao\DAO $dao, object $model, $prefix, $excludedFields, $defaultValues, $externalFieldsDefinition, $multipleAssociationsDefinition)
    {
        $modelDefinition = $dao->getModelDefinition();
        $fieldDefinitions = $modelDefinition->getFieldDefinitions();
        foreach($fieldDefinitions as $field => $fieldDefinition){
            $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
            $requestField = $prefix.$field;
            if(!in_array($field, $excludedFields) && (!isset($fieldDefinition['editingDisabled']) || !$fieldDefinition['editingDisabled'])){
                if(isset($fieldDefinition['lang']) && $fieldDefinition['lang']){
                    $langValues = $this->input->getPostParam($requestField);
                    $langValues = is_array($langValues) ? $langValues : array();
                    $modelDefinition->setAllLangsPropertyValues($model, $field, $langValues);
                }elseif(isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::EXISTING_MODEL_ID) &&
                    isset($externalFieldsDefinition[$field]) && isset($externalFieldsDefinition[$field]['editionType']) &&
                    ($externalFieldsDefinition[$field]['editionType'] == ExternalFieldEditionType::ALL_FIELDS))
                {
                    $externalDao = $dao->getForeignDAO($field);
                    $externalModel = null;
                    if($modelDefinition->hasAssociatedModel($model, $field)){
                        $externalModel = $modelDefinition->getAssociatedModel($model, $field);
                    }else{
                        $externalModel = $externalDao->createModel();
                        $modelDefinition->setAssociatedModel($model, $field, $externalModel);
                    }
                    $subExternalFieldDefinition = isset($externalFieldsDefinition[$field]['externalFieldsDefinition']) ? $externalFieldsDefinition[$field]['externalFieldsDefinition'] : null;
                    $subMultipleAssocations = isset($externalFieldsDefinition[$field]['multipleAssociationsDefinition']) ? $externalFieldsDefinition[$field]['multipleAssociationsDefinition'] : null;
                    $subDefaultValues = isset($externalFieldsDefinition[$field]['defaultValues']) ? $externalFieldsDefinition[$field]['defaultValues'] : null;
                    $subExcludedFields = isset($externalFieldsDefinition[$field]['excludedFields']) ? $externalFieldsDefinition[$field]['excludedFields'] : array();
                    $externalSaveConfig = $saveConfig->createAssociatedFieldSaveConfig($field);
                    $this->retrieveModelData($update, $externalSaveConfig, $externalDao, $externalModel, $requestField.'_', $subExcludedFields, $subDefaultValues, $subExternalFieldDefinition, $subMultipleAssocations);
                }else{
                    $value = $this->input->getPostParam($requestField);
                    if((($nature == FieldNature::IMAGE) || ($nature == FieldNature::FILE))){
                        $savedValue = $this->input->getPostParam('saved_'.$requestField);
                        if($modelDefinition->isLoaded($model)){
                            if(empty($savedValue)){
                                $modelDefinition->setPropertyValue($model, $field, $value);
                            }
                        }else{
                            $modelDefinition->setPropertyValue($model, $field, $value);
                        }
                    }else{
                        if(($nature == FieldNature::PASSWORD) && !empty($value)){
                            $value = App::getTools()->encrypt($value);
                        }
                        $modelDefinition->setPropertyValue($model, $field, $value);
                    }
                }
            }elseif (!$update && isset($defaultValues[$field])){
                $modelDefinition->setPropertyValue($model, $field, $defaultValues[$field]);
            }
        }
        if(!empty($multipleAssociationsDefinition)){
            
            foreach ($multipleAssociationsDefinition as $associationName => $multipleAssociationDefinition) {
                if(isset($multipleAssociationDefinition['field'])){
                    $multipleAssociationModelDefinition = $modelDefinition->getMultipleAssociationDefinition($associationName);
                    $associatedModels = array();
                    $values = $this->input->getPostParam($prefix.$associationName);
                    if (is_array($values)) {
                        foreach ($values as $value) {
                            $newModel = $multipleAssociationModelDefinition->createModel();
                            $multipleAssociationModelDefinition->setPropertyValue($newModel, $multipleAssociationDefinition['field'], $value);
                            $associatedModels[] = $newModel;
                        }
                    }
                    $modelDefinition->setMultipleAssociatedModels($model, $associationName, $associatedModels);
                    $saveConfig->createMultipleSaveAssociation($associationName);
                }
            }
        }
    }
    
    public function finalizeUploadDefault($success, \muuska\model\ModelDefinition $modelDefinition, \muuska\dao\DAO $dao, $defaultModel, $loadedModel, $excludedFields, $externalFieldsDefinition = null) {
        $modelFieldsChanged = array();
        $fieldsDefinition = $modelDefinition->getFieldDefinitions();
        foreach($fieldsDefinition as $field => $fieldDefinition){
            $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
            if(($nature == FieldNature::EXISTING_MODEL_ID) && isset($externalFieldsDefinition[$field]) && isset($externalFieldsDefinition[$field]['editionType']) && ($externalFieldsDefinition[$field]['editionType'] == ExternalFieldEditionType::ALL_FIELDS)){
                $externalDao = $dao->getForeignDAO($field);
                $subExternalFieldDefinition = isset($externalFieldsDefinition[$field]['externalFieldsDefinition']) ? $externalFieldsDefinition[$field]['externalFieldsDefinition'] : null;
                $subExcludedFields = isset($subExternalFieldDefinition['excludedFields']) ? $subExternalFieldDefinition['excludedFields'] : array();
                $externalLoadedModel = ($loadedModel !== null) ? $modelDefinition->getAssociatedModel($loadedModel, $field) : null;
                $this->finalizeUploadDefault($success, $externalDao->getModelDefinition(), $externalDao, $modelDefinition->getAssociatedModel($defaultModel, $field), $externalLoadedModel, $subExcludedFields, $subExternalFieldDefinition);
            }elseif((($nature == FieldNature::IMAGE) || ($nature == FieldNature::FILE)) && !in_array($field, $excludedFields)){
                $changed = false;
                if($success){
                    $currentValue = $modelDefinition->getPropertyValue($defaultModel, $field);
                    if(($loadedModel !== null) && $modelDefinition->isLoaded($loadedModel)){
                        $savedValue = $modelDefinition->getPropertyValue($loadedModel, $field);;
                        if(!empty($savedValue) && ($savedValue != $currentValue)){
                            @unlink(App::getApp()->getModelFullFile($modelDefinition, $loadedModel, $field));
                        }
                        if(!empty($currentValue) && ($savedValue != $currentValue)){
                            $changed = $this->moveModelFile($modelDefinition, $defaultModel, $field, $currentValue);
                        }
                    }elseif(!empty($currentValue)){
                        $changed = $this->moveModelFile($modelDefinition, $defaultModel, $field, $currentValue);
                    }
                }
                if($changed){
                    $modelFieldsChanged[] = $field;
                }
            }
        }
        if(!empty($modelFieldsChanged)){
            $saveConfig = $dao->createSaveConfig();
            $saveConfig->setSpecificFields($modelFieldsChanged);
            $dao->update($defaultModel, $saveConfig);
        }
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $model
     * @param string $field
     * @param string $currentFile
     * @return boolean
     */
    public function moveModelFile(\muuska\model\ModelDefinition $modelDefinition, $model, $field, $currentFile) {
        $result = true;
        $extension = App::getFileTools()->getFileExtension($currentFile);
        $newFileName = $modelDefinition->getSinglePrimaryValue($model).'.'.$extension;
        $modelDefinition->setPropertyValue($model, $field, $newFileName);
        App::getFileTools()->createDirectoryIfNotExist(App::getApp()->getModelFileDir($modelDefinition, $field));
        $result = rename(App::getApp()->getUploadTmpFullFile($currentFile), App::getApp()->getModelFullFile($modelDefinition, $model, $field));
        return $result;
    }
}