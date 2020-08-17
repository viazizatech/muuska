<?php
namespace muuska\model;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\constants\FolderPath;
use muuska\constants\Separator;
use muuska\dao\constants\DAOFunctionCode;
use muuska\project\constants\ProjectType;
use muuska\util\App;
use muuska\validation\constants\ValidationErrorCode;

class AbstractModelDefinition implements ModelDefinition{
    
    private $definitionTmpInfos;
    
    const MODEL_TYPE_ARRAY = 'array';
    const MODEL_TYPE_PUBLIC_FIELDS = 'pulic_fields';
    const MODEL_TYPE_DEFAULT = 'default';
    
    protected function __construct() {}
    
    /**
     * @return array
     */
    protected function createDefinition() {}
    
    protected function getDefinition() {
        if(!isset($this->definitionTmpInfos['definition'])){
            $this->definitionTmpInfos['definition'] = $this->createDefinition();
        }
        return $this->definitionTmpInfos['definition'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getArrayDefinition()
     */
    public function getArrayDefinition() {
        return $this->getDefinition();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::createModel()
     */
    public function createModel() {
        $result = null;
        $definition = $this->getDefinition();
        if(isset($definition['modelType']) && ($definition['modelType'] === self::MODEL_TYPE_ARRAY)){
            $result = App::models()->createArrayModel();
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getAssociationDefinition()
     */
    public function getAssociationDefinition($field) {
        $result = null;
        $fieldDefinition = $this->getFieldDefinition($field);
        if(isset($fieldDefinition['reference'])){
            $result = $fieldDefinition['reference'];
        }elseif(isset($fieldDefinition['referenceClass'])){
            $result = $fieldDefinition['referenceClass']::getInstance();
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getMultipleAssociationDefinition()
     */
    public function getMultipleAssociationDefinition($associationName) {
        $result = null;
        $definition = $this->getDefinition();
        if(isset($definition['associations']) && isset($definition['associations'][$associationName])){
            if(isset($definition['associations'][$associationName]['reference'])){
                $result = $definition['associations'][$associationName]['reference'];
            }elseif(isset($definition['associations'][$associationName]['referenceClass'])){
                $result = $definition['associations'][$associationName]['referenceClass']::getInstance();
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getMultipleAssociationField()
     */
    public function getMultipleAssociationField($associationName) {
        $definition = $this->getDefinition();
        return (isset($definition['associations']) && isset($definition['associations'][$associationName]) && isset($definition['associations'][$associationName]['field'])) ? $definition['associations'][$associationName]['field'] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::formatFields()
     */
    public function formatFields(object $model, \muuska\dao\DAO $dao, $languages = array(), $defaultLang = '') {
        $fieldsDefinition = $this->getFieldDefinitions();
        foreach ($fieldsDefinition as $fieldName => $fieldDefinition) {
            $purify = false;
            $isLangField = $this->isLangField($fieldName);
            if(isset($fieldDefinition['validationRule'])){
                $purify = ($fieldDefinition['validationRule'] === 'isCleanHtml');
            }elseif(isset($fieldDefinition['validationRules'])){
                $purify = in_array('isCleanHtml', $fieldDefinition['validationRules']);
            }
            $value = $isLangField ? $this->getAllLangsPropertyValues($model, $fieldName) : $this->getPropertyValue($model, $fieldName);
            if($isLangField && empty($value)){
                $value[$defaultLang] = $this->getPropertyValue($model, $fieldName);
            }
            if(isset($fieldDefinition['default'])){
                if($isLangField){
                    if(App::validations()->getValidatorUtilInstance()->isMultilingualValueEmpty($fieldDefinition, $value)){
                        foreach ($value as $key => $val) {
                            $value[$key] = $fieldDefinition['default'];
                        }
                    }
                }else{
                    if(App::validations()->getValidatorUtilInstance()->isFieldValueEmpty($fieldDefinition, $value)){
                        $value = $fieldDefinition['default'];
                    }
                }
            }
            if($isLangField && !is_array($value)){
                $value = array($defaultLang => $value);
            }
            if (isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::EXISTING_MODEL_ID) && empty($value)) {
                $value = null;
            }else{
                $value = is_array($value) ? $value : array($value);
                foreach($value as $key => $val){
                    $value[$key] = $this->formatValue($dao, $val, $fieldDefinition['type'], false, $purify);
                }
                if(!$isLangField && isset($value[0])){
                    $value = $value[0];
                }
            }
            if($isLangField){
                $this->setAllLangsPropertyValues($model, $fieldName, $value);
                $this->fillMultilingualEmptyFields($model, $fieldName, $languages, $defaultLang);
            }else{
                $this->setPropertyValue($model, $fieldName, $value);
            }
        }
    }
    protected function fillMultilingualEmptyFields($model, $field, $languages, $defaultLang)
    {
        $defaultValue = '';
        $values = $this->getAllLangsPropertyValues($model, $field);
        if(empty($values)){
            $defaultValue = $this->getPropertyValue($model, $field);
            $values = array();
        }
        $defaultValue = ((isset($values[$defaultLang]) && (!empty($values[$defaultLang]))) ? $values[$defaultLang] : $defaultValue);
        //Find not empty value
        if (empty($defaultValue)) {
            foreach ($values as $value) {
                if (!empty($value)) {
                    $defaultValue= $value;
                    break;
                }
            }
        }
        foreach ($values as $key => $value) {
            if (empty($value)) {
                $values[$key] = $defaultValue;
            }
            unset($languages[$key]);
        }
        foreach ($languages as $langObject) {
            $lang = $langObject->getUniqueCode();
            if (!isset($values[$lang])) {
                $values[$lang] = $defaultValue;
            }
        }
        $this->setAllLangsPropertyValues($model, $field, $values);
    }
    
    public function validateModel(\muuska\validation\input\ModelValidationInput $input){
        $saveConfig = $input->getSaveConfig();
        $dao = $input->getDao();
        $model = $input->getValue();
        $valid = true;
        $fieldResults = array();
        $langFieldResults = array();
        $allFieldResults = array();
        $errors = array();
        $fieldsToValidate = (($saveConfig === null) || !$saveConfig->hasSpecificFields()) ? array() : $saveConfig->getSpecificFields();
        $fieldsToExclude = (($saveConfig === null) || !$saveConfig->hasExcludedFields()) ? array() : $saveConfig->getExcludedFields();
        $useFieldsToValidate = !empty($fieldsToValidate);
        if(($saveConfig !== null) && $saveConfig->isMultipleAssociation() && $input->hasParentInput()){
            $fieldsToExclude[] = $input->getParentInput()->getDao()->getModelDefinition()->getMultipleAssociationField($saveConfig->getAssociationName());
        }
        $associatedExternalFieldsToValidate = array();
        $fieldsDefinition = $this->getFieldDefinitions();
        foreach ($fieldsDefinition as $fieldName => $fieldDefinition) {
            if(isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::EXISTING_MODEL_ID) && ($saveConfig !== null) && $saveConfig->hasAssociatedFieldSaveConfig($fieldName)){
                $fieldsToExclude[] = $fieldName;
                $associatedExternalFieldsToValidate[] = $fieldName;
            }
            if(!in_array($fieldName, $fieldsToExclude) && (!$useFieldsToValidate || in_array($fieldName, $fieldsToValidate))){
                if($this->isLangField($fieldName)){
                    $langFieldResults[$fieldName] = $this->validateField($fieldName, $input);
                    $allFieldResults[$fieldName] = $langFieldResults[$fieldName]->isValid();
                }else{
                    $fieldResults[$fieldName] = $this->validateField($fieldName, $input);
                    $allFieldResults[$fieldName] = $fieldResults[$fieldName]->isValid();
                }
                if(!$allFieldResults[$fieldName]){
                    $valid = false;
                }
            }
        }
        
        $multipleUniques = $this->getMultipleUniques();
        if(!empty($multipleUniques) && ($dao !== null)){
            foreach ($multipleUniques as $uniques) {
                $checkUnique = false;
                foreach ($uniques as $field) {
                    if (isset($allFieldResults[$field]) && $allFieldResults[$field]) {
                        $checkUnique = true;
                        break;
                    }
                }
                if($checkUnique && !$dao->checkUnique($model, $uniques, $input->isUpdate(), $saveConfig)){
                    $errors = array(ValidationErrorCode::MULTIPLE_UNIQUE => sprintf(App::validations()->getValidatorUtilInstance()->getErrorText(ValidationErrorCode::MULTIPLE_UNIQUE, $input->getLang())), implode(', ', $uniques));
                    $valid = false;
                }
            }
        }
        
        $associatedResults = array();
        foreach ($associatedExternalFieldsToValidate as $fieldName) {
            $associatedModelDefinition = $this->getAssociationDefinition($fieldName);
            $associatedModel = $this->getAssociatedModel($model, $fieldName);
            if($associatedModel === null){
                if(isset($fieldsDefinition[$fieldName]) && isset($fieldsDefinition[$fieldName]['required']) && $fieldsDefinition[$fieldName]['required']){
                    $valid = false;
                }
            }else{
                $associatedDao = ($dao !== null) ? $dao->getForeignDAO($fieldName) : null;
                $associatedUpdate = !empty($this->getPropertyValue($model, $fieldName));
                $associatedSaveConfig = $saveConfig->getAssociatedFieldSaveConfig($fieldName);
                $associatedResults[$fieldName] = $associatedModelDefinition->validateModel(App::validations()->createModelValidationInput($associatedModel, $input->getLang(), $associatedDao, $associatedSaveConfig, $associatedUpdate, $input));
                if(!$associatedResults[$fieldName]->isValid()){
                    $valid = false;
                }
            }
        }
        $multipleAssociatedResults = array();
        if(($saveConfig !== null) && $saveConfig->hasMultipleSaveAssociations()){
            $multipleSaveAssociations = $saveConfig->getMultipleSaveAssociations();
            foreach ($multipleSaveAssociations as $key => $multipleSaveAssociation) {
                $multipleModels = $this->getMultipleAssociatedModels($model, $key);
                $multipleAssociatedDefinition = $this->getMultipleAssociationDefinition($key);
                if(($multipleAssociatedDefinition !== null) && !empty($multipleModels)){
                    $associatedDao = ($dao !== null) ? $dao->getMultipleAssociationDao($key) : null;
                    foreach ($multipleModels as $modelKey => $multipleModel) {
                        $associatedSaveConfig = $multipleSaveAssociation->getModelSpecificSaveConfig($modelKey, true);
                        $associatedUpdate = $multipleAssociatedDefinition->isUpdateRequired($multipleModel, $associatedSaveConfig);
                        $newResult = $multipleAssociatedDefinition->validateModel(App::validations()->createModelValidationInput($multipleModel, $input->getLang(), $associatedDao, $associatedSaveConfig, $associatedUpdate, $input));                        
                        $multipleAssociatedResults[$key][] = $newResult;
                        if($newResult->isValid()){
                            $valid = false;
                        }
                    }
                }
            }
        }
        return App::validations()->createDefaultModelValidationResult($valid, $fieldResults, $errors, null, $langFieldResults, $associatedResults, $multipleAssociatedResults);
    }
    
    public function validateField($field, \muuska\validation\input\ModelValidationInput $input){
        $result = null;
        $fieldDefinition = $this->getFieldDefinition($field);
        $object = $input->getValue();
        $simpleValue = $this->getPropertyValue($object, $field);
        if($this->isLangField($field)){
            $values = $this->getAllLangsPropertyValues($object, $field);
            if(empty($values) && !empty($simpleValue)){
                $values[$input->getLang()] = $simpleValue;
            }
            $result = App::validations()->getValidatorUtilInstance()->validateLangField($fieldDefinition, App::validations()->createDefaultValidationInput($values, $input->getLang()), $object);
        }else{
            $result = App::validations()->getValidatorUtilInstance()->validateField($fieldDefinition, App::validations()->createDefaultValidationInput($simpleValue, $input->getLang()), $object);
        }
        if(($result !== null) && $result->isValid() && $input->hasDao() && isset($fieldDefinition['unique']) && $fieldDefinition['unique']){
            if(!$input->getDao()->checkUnique($object, array($field), $input->isUpdate(), $input->getSaveConfig())){
                $errors = array(ValidationErrorCode::UNIQUE => App::validations()->getValidatorUtilInstance()->getErrorText(ValidationErrorCode::UNIQUE, $input->getLang()));
                $result = $this->isLangField($field) ? App::validations()->createDefaultLangFieldValidationResult(false, array(), $errors) : App::validations()->createDefaultValidationResult(false, $errors);
            }
        }
        return $result;
    }
    
    public function formatValue(\muuska\dao\DAO $dao, $value, $type, $with_quotes = false, $purify = true, $allow_null = false){
        if ($allow_null && $value === null) {
            return null;
        }
        
        switch ($type) {
            case DataType::TYPE_INT:
                return (int)$value;
                
            case DataType::TYPE_BOOL:
                return (int)$value;
                
            case DataType::TYPE_FLOAT:
                return (float)str_replace(',', '.', $value);
                
            case DataType::TYPE_DECIMAL:
                return (float)str_replace(',', '.', $value);
                
            case DataType::TYPE_DATE:
                if (!$value) {
                    $value = '0000-00-00';
                }
                
                if ($with_quotes) {
                    return '\''.$dao->protectString($value).'\'';
                }
                return $dao->protectString($value);
                
            case DataType::TYPE_HTML:
                if ($purify) {
                    $value = App::getTools()->purifyHTML($value);
                }
                if ($with_quotes) {
                    return '\''.$dao->protectString($value, true).'\'';
                }
                return $dao->protectString($value, true);
                
            case DataType::TYPE_SQL:
                if ($with_quotes) {
                    return '\''.$dao->protectString($value, true).'\'';
                }
                return $dao->protectString($value, true);
                
            case DataType::TYPE_NOTHING:
                return $value;
                
            case DataType::TYPE_STRING:
            default :
                if ($with_quotes) {
                    return '\''.$dao->protectString($value).'\'';
                }
                return $dao->protectString($value);
        }
    }
    
    public function containsField($field){
        return isset($this->getFieldDefinitions()[$field]);
    }
    
    public function getFieldDefinitions(){
        $definition = $this->getDefinition();
        return $definition['fields'];
    }
    
    public function hasForeignFields(){
        $found = false;
        $fieldsDefinition = $this->getFieldDefinitions();
        foreach($fieldsDefinition as $fieldDefinition){
            if(isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::EXISTING_MODEL_ID)){
                $found = true;
                break;
            }
        }
        return $found;
    }
    
    public function getFieldDefinition($field){
        $fieldsDefinition = $this->getFieldDefinitions();
        return isset($fieldsDefinition[$field]) ? $fieldsDefinition[$field] : array();
    }
    
    public function getFields(){
        return array_keys($this->getFieldDefinitions());
    }
    
    public function getLangFields() {
        $this->splitFields();
        return $this->definitionTmpInfos['splittedFields']['lang'];
    }
    
    public function getSimpleFields() {
        $this->splitFields();
        return $this->definitionTmpInfos['splittedFields']['simple'];
    }
    
    protected function splitFields() {
        if(!isset($this->definitionTmpInfos['splittedFields'])){
            $this->definitionTmpInfos['splittedFields'] = array('lang' => array(), 'simple' => array());
            $fields = $this->getFields();
            foreach ($fields as $fieldName) {
                if($this->isLangField($fieldName)){
                    $this->definitionTmpInfos['splittedFields']['lang'][] = $fieldName;
                }else{
                    $this->definitionTmpInfos['splittedFields']['simple'][] = $fieldName;
                }
            }
        }
    }
    
    public function isLangField($field) {
        $fieldDefinition = $this->getFieldDefinition($field);
        return $this->isMultilingual() && isset($fieldDefinition['lang']) && $fieldDefinition['lang'];
    }
    
    public function isMultilingual() {
        $definition = $this->getDefinition();
        return isset($definition['multilingual']) && $definition['multilingual'];
    }
    
    public function isAutoIncrement() {
        $definition = $this->getDefinition();
        return isset($definition['autoIncrement']) && $definition['autoIncrement'];
    }
    
    public function getName(){
        $definition = $this->getDefinition();
        return $definition['name'];
    }
    
    public function getFullName(){
        return App::getApp()->getModelFullName($this->getName(), $this->getProjectType(), $this->getProjectName());
    }
    
    public function getSinglePrimary(){
        if(!isset($this->definitionTmpInfos['singlePrimaryField'])){
            $definition = $this->getDefinition();
            $this->definitionTmpInfos['singlePrimaryField'] = $this->hasMultiplePrimary() ? implode(Separator::PRIMARIES_FIELD, $definition['primaries']) : $this->getPrimary();
        }
        return $this->definitionTmpInfos['singlePrimaryField'];
    }
    public function getPrimary(){
        $definition = $this->getDefinition();
        return isset($definition['primary']) ? $definition['primary'] : '';
    }
    
    public function getPrimaries(){
        $result = array();
        $definition = $this->getDefinition();
        if(isset($definition['primary'])){
            $result = array($definition['primary']);
        }elseif (isset($definition['primaries'])){
            $result = $definition['primaries'];
        }
        return $result;
    }
    
    public function hasMultiplePrimary(){
        $definition = $this->getDefinition();
        return isset($definition['primaries']);
    }
    
    public function getPrimaryValuesFromString($string){
        $value = array();
        $definition = $this->getDefinition();
        if($this->hasMultiplePrimary()){
            $values = explode(Separator::PRIMARIES_DATA, $string);
            foreach($definition['primaries'] as $key => $field){
                if(isset($values[$key])){
                    $value[$field] = $values[$key];
                }else{
                    throw new \Exception('data does not match primaries');
                }
            }
        }else{
            $value[$definition['primary']] = $string;
        }
        return $value;
    }
    
    public function getModelPresentation(object $model){
        $presentationFields = $this->getPresentationFields();
        $presentationValue = '';
        foreach($presentationFields as $field){
            $value = $this->getPropertyValue($model, $field);
            if(!empty($presentationValue)){
                $presentationValue .= $this->getPresentationFieldsSeparator($field);
            }
            $presentationValue .= $value;
        }
        return $presentationValue;
    }
    
    public function getObjectTypeForUpload(){
        $clasName = get_class($this);
        $result = str_replace('\\', ':', $clasName);
        return $result;
    }
    
    public function getPresentationFields(){
        $presentationFields = array();
        $definition = $this->getDefinition();
        
        if(isset($definition['presentationFields'])){
            $presentationFields = $definition['presentationFields'];
        }elseif(isset($definition['presentationField'])){
            $presentationFields = array($definition['presentationField']);
        }else{
            if($this->containsField('firstName') && $this->containsField('lastName')){
                $presentationFields = array('firstName', 'lastName');
            }elseif($this->containsField('label')){
                $presentationFields[] = 'label';
            }elseif($this->containsField('displayName')){
                $presentationFields[] = 'displayName';
            }elseif($this->containsField('name')){
                $presentationFields[] = 'name';
            }elseif($this->containsField('title')){
                $presentationFields[] = 'title';
            }elseif($this->containsField('code')){
                $presentationFields[] = 'code';
            }elseif($this->containsField('reference')){
                $presentationFields[] = 'reference';
            }else{
                $presentationFields = $this->getPrimaries();
            }
        }
        return $presentationFields;
    }
    
    public function getPresentationFieldsSeparator($currentField = ''){
        $definition = $this->getDefinition();
        return isset($definition['presentationFieldsSeparator']) ? $definition['presentationFieldsSeparator'] : ' ';
    }
    
    public function createPresentationFieldParameter($asExternal = false, $associatedFieldName = ''){
        $fieldParameter = App::daos()->createFieldParameter('');
        return $this->formatPresentationFieldParameter($fieldParameter);;
    }
    
    public function formatPresentationFieldParameter(\muuska\dao\util\FieldParameter $fieldParameter, $asExternal = false, $associatedFieldName = ''){
        $presentationFields = $this->getPresentationFields();
        if(count($presentationFields) > 1){
            $doaFunction = $fieldParameter->createDaoFunctionFromCode(DAOFunctionCode::CONCAT);
            $first = true;
            foreach($presentationFields as $field){
                if($first){
                    $first = false;
                }else{
                    $doaFunction->addSimpleParameter($this->getPresentationFieldsSeparator($field));
                }
                $fieldName = $field;
                $foreign = false;
                $externalField = null;
                if($asExternal){
                    $fieldName = $associatedFieldName;
                    $foreign = true;
                    $externalField = $field;
                }
                $doaFunction->addFieldParameter($fieldName, $foreign, $externalField);
            }
        }else{
            $field = App::getArrayTools()->getFirstValue($presentationFields);
            if($asExternal){
                $fieldParameter->setFieldName($associatedFieldName);
                $fieldParameter->setExternalField($field);
                $fieldParameter->setForeign(true);
            }else{
                $fieldParameter->setFieldName($field);
            }
        }
    }
    
    public function getSubFolderPath($field){
        $path = '';
        if($this->containsField($field)){
            $fieldDefinition = $this->getFieldDefinition($field);
            if(!isset($fieldDefinition['noSubfolder']) || !$fieldDefinition['noSubfolder']){
                if(isset($fieldDefinition['subfolderPath'])){
                    $path = $fieldDefinition['subfolderPath'];
                }else{
                    $path = App::getStringTools()->toUnderscoreCase($field);
                }
            }
        }
        return $path;
    }
    
    public function getFilePath($field){
        $definition = $this->getDefinition();
        $finalPath = '';
        $fieldDefinition = $this->getFieldDefinition($field);
        if(isset($fieldDefinition['filePath'])){
            $finalPath = $fieldDefinition['filePath'];
        }else{
            $defaultBasePath = '';
            if(isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::IMAGE))
            {
                $defaultBasePath = FolderPath::MODEL_IMAGE;
            }
            if(empty($defaultBasePath)){
                $defaultBasePath = FolderPath::MODEL_FILE;
            }
            if(!App::getStringTools()->endsWith($defaultBasePath, '/')){
                $defaultBasePath .= '/';
            }
            $project = $this->getProject();
            /*$projectConcat = ($project !== null) ? $project->getSubPathInApp() : '';*/
            $projectConcat = '';
            if($project !== null){
                $projectConcat .= strtolower(substr($project->getType(), 0, 1));
                $projectName = $project->getName();
                if(!empty($projectName)){
                    $projectConcat .= '/'.$projectName;
                }
            }
            $objectPath = isset($definition['fileFolder']) ? $definition['fileFolder'] : $this->getName();
            $defaultBasePath .= $projectConcat.'/'.$objectPath.'/';
            $subfolderPath = $this->getSubFolderPath($field);
            $finalPath = $defaultBasePath . (empty($subfolderPath) ? '' : $subfolderPath);
        }
        if(!empty($finalPath) && !App::getStringTools()->endsWith($finalPath, '/')){
            $finalPath .= '/';
        }
        return $finalPath;
    }
    
    public function getFileFullPath(object $model, $field){
        return $this->getFilePath($field).$this->getPropertyValue($model, $field);
    }
    
    public function hasImage(object $model, $field)
    {
        $value = $this->getPropertyValue($model, $field);
        return !empty($value);
    }
    public function hasMainImage(object $model)
    {
        $result = false;
        $mainImageField = $this->getMainImageField();
        if(!empty($mainImageField)){
            $result = $this->hasImage($model, $mainImageField);
        }
        return $result;
    }
    public function getMainImageField()
    {
        $definition = $this->getDefinition();
        $field = '';
        if(isset($definition['mainImageField'])){
            $field = $definition['mainImageField'];
        }elseif(isset($definition['fields']['logo'])){
            $field = 'logo';
        }elseif(isset($definition['fields']['coverImage'])){
            $field = 'coverImage';
        }elseif(isset($definition['fields']['image'])){
            $field = 'image';
        }elseif(isset($definition['fields']['photo'])){
            $field = 'photo';
        }elseif(isset($definition['fields']['avatar'])){
            $field = 'avatar';
        }elseif(isset($definition['fields']['picture'])){
            $field = 'picture';
        }
        return $field;
    }
    
    /**
     * @return string
     */
    public function getProjectType(){
        $definition = $this->getDefinition();
        return isset($definition['projectType']) ? $definition['projectType'] : ProjectType::APPLICATION;
    }
    /**
     * @return string
     */
    public function getProjectName(){
        $definition = $this->getDefinition();
        return isset($definition['projectName']) ? $definition['projectName'] : null;
    }
    
    public function getProject(){
        return App::getApp()->getProject($this->getProjectType(), $this->getProjectName());
    }
    
    public function createModelFromArray($array){
        
    }
    
    public function getPropertyValue(object $model, $field){
        $result = null;
        $definition = $this->getDefinition();
        if(isset($definition['modelType']) && ($definition['modelType'] === self::MODEL_TYPE_ARRAY)){
            $result = $model->getPropertyValue($field);
        }elseif(isset($definition['modelType']) && ($definition['modelType'] === self::MODEL_TYPE_PUBLIC_FIELDS)){
            $result = $model->$field;
        }else{
            $fieldDefinition = $this->getFieldDefinition($field);
            $getterName = ((isset($fieldDefinition['type']) && ($fieldDefinition['type'] == DataType::TYPE_BOOL)) ? 'is' : 'get') . ucfirst($field);
            $result = $model->$getterName();
        }
        return $result;
    }
    
    public function setPropertyValue(object $model, $field, $value){
        $result = null;
        $definition = $this->getDefinition();
        if(isset($definition['modelType']) && ($definition['modelType'] === self::MODEL_TYPE_ARRAY)){
            $result = $model->setPropertyValue($field, $value);
        }elseif(isset($definition['modelType']) && ($definition['modelType'] === self::MODEL_TYPE_PUBLIC_FIELDS)){
            $result = $model->$field = $value;
        }else{
            $setterName = 'set'.ucfirst($field);
            $result = $model->$setterName($value);
        }
        return $result;
    }
    
    public function hasAllLangPropertyValues(object $model, $field){
        $result = false;
        if($model instanceof AbstractModel){
            $result = $model->hasAllLangPropertyValues($field);
        }
        return $result;
    }
    
    public function hasPropertyValueByLang(object $model, $field, $lang){
        $result = false;
        if($model instanceof AbstractModel){
            $result = $model->hasPropertyValueByLang($field, $lang);
        }
        return $result;
    }
    
    public function getAllLangsPropertyValues(object $model, $field){
        $result = array();
        if($model instanceof AbstractModel){
            $result = $model->getAllLangsPropertyValues($field);
        }
        return $result;
    }
    
    public function getPropertyValueByLang(object $model, $field, $lang){
        $result = null;
        if($model instanceof AbstractModel){
            $result = $model->getPropertyValueByLang($field, $lang);
        }
        return $result;
    }
    
    public function setAllLangsPropertyValues(object $model, $field, $values){
        if($model instanceof AbstractModel){
            $model->setAllLangsPropertyValues($field, $values);
        }
    }
    
    public function setPropertyValueByLang(object $model, $field, $value, $lang){
        if($model instanceof AbstractModel){
            $model->setPropertyValueByLang($field, $value, $lang);
        }
    }
    
    public function setAssociatedModel(object $model, $field, object $associatedobject){
        if($model instanceof AbstractModel){
            $model->setAssociated($field, $associatedobject);
        }
    }
    
    public function hasAssociatedModel(object $model, $field){
        $result = false;
        if($model instanceof AbstractModel){
            $result = $model->hasAssociated($field);
        }
        return $result;
    }
    
    public function getAssociatedModel(object $model, $field){
        $result = null;
        if($model instanceof AbstractModel){
            $result = $model->getAssociated($field);
        }
        return $result;
    }
    
    public function addMultipleAssociated(object $model, $associationName, object $associatedModel){
        if($model instanceof AbstractModel){
            $model->addMultipleAssociated($associationName, $associatedModel);
        }
    }
    
    public function setMultipleAssociatedModels(object $model, $associationName, $associatedModels){
        if($model instanceof AbstractModel){
            $model->setMultipleAssociatedModels($associationName, $associatedModels);
        }
    }
    
    public function getMultipleAssociatedModels(object $model, $associationName){
        $result = array();
        if($model instanceof AbstractModel){
            $result = $model->getMultipleAssociatedModels($associationName);
        }
        return $result;
    }
    
    public function toArray(object $model){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getPrimaryValues()
     */
    public function getPrimaryValues(object $model){
        $value = array();
        $primaries = $this->getPrimaries();
        foreach($primaries as $primary){
            $value[$primary] = $this->getPropertyValue($model, $primary);
        }
        return $value;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getPrimaryValue()
     */
    public function getPrimaryValue(object $model){
        return $this->getPropertyValue($model, $this->getPrimary());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::setPrimaryValue()
     */
    public function setPrimaryValue(object $model, $value){
        $this->setPropertyValue($model, $this->getPrimary(), $value);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::isLoaded()
     */
    public function isLoaded(object $model){
        $result = true;
        if($model === null){
            $result = false;
        }else{
            $primaries = $this->getPrimaries();
            foreach($primaries as $primary){
                $value = $this->getPropertyValue($model, $primary);
                if(empty($value)){
                    $result = false;
                    break;
                }
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::isUpdateRequired()
     */
    public function isUpdateRequired($model, \muuska\dao\util\SaveConfig $saveConfig = null){
        $result = false;
        if($this->isAutoIncrement()){
            $result = $this->isLoaded($model);
        }else{
            if(!$this->hasMultiplePrimary()){
                if(($saveConfig !== null) && $saveConfig->hasRestrictionField($this->getPrimary())){
                    $result = true;
                }else{
                    $fieldDefinition = $this->getFieldDefinition($this->getPrimary());
                    if(isset($fieldDefinition['editingDisabled']) && $fieldDefinition['editingDisabled']){
                        $result = $this->isLoaded($model);
                    }
                }
            }elseif(($saveConfig !== null) && $saveConfig->hasRestrictionForFields($this->getPrimaries())){
                $result = true;
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::duplicateModel()
     */
    public function duplicateModel(object $model){
        return clone $model;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::createUrlInput()
     */
    public function createUrlInput(object $model, \muuska\controller\ControllerInput $controllerInput, $action, $params, $currentControllerNameEnabled = false, $anchor = '', $mode = null){
        $definition = $this->getDefinition();
        $controller = $currentControllerNameEnabled ? $controllerInput->getName() : '';
        $subAppName = $controllerInput->getSubAppName();
        $paramAdded = false;
        $projectType = $currentControllerNameEnabled ? $controllerInput->getProject()->getType() : $this->getProjectType();
        $projectName = $currentControllerNameEnabled ? $controllerInput->getProject()->getName() : $this->getProjectName();
        if(isset($definition['url']) && isset($definition['url'][$subAppName])) {
            $urlDefinition = $definition['url'][$subAppName];
            if(!$currentControllerNameEnabled && isset($urlDefinition['controller'])){
                $controller = $urlDefinition['controller'];
            }
            if(isset($urlDefinition['fieldsByAction']) && isset($urlDefinition['fieldsByAction'][$action])){
                foreach ($urlDefinition['fieldsByAction'] as $field) {
                    $params[$field] = $this->getPropertyValue($model, $field);
                }
                $paramAdded = true;
            }
            if(!$currentControllerNameEnabled){
                if(isset($urlDefinition['projectType'])){
                    $projectType = $urlDefinition['projectType'];
                }
                if(isset($urlDefinition['projectName'])){
                    $projectName = $urlDefinition['projectName'];
                }
            }
        }
        if(empty($controller)){
            $controller = str_replace('_', '-', $this->getName());
        }
        if(!$paramAdded){
            $params = array_merge($params, $this->getPrimaryValues($model));
        }
        return App::urls()->createUrlCreationInput($subAppName, $controllerInput->getLang(), $controller, $action, $params, $projectType, $projectName, $anchor, $controllerInput->getVariationTriggers(), $mode);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getSpecificDAOSource()
     */
    public function getSpecificDAOSource()
    {
        $definition = $this->getDefinition();
        return isset($definition['daoSource']) ? $definition['daoSource'] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::hasSpecificDAOSource()
     */
    public function hasSpecificDAOSource()
    {
        return !empty($this->getSpecificDAOSource());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getCreationDateField()
     */
    public function getCreationDateField()
    {
        return 'creationDate';
    }

    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getLastModifiedDateField()
     */
    public function getLastModifiedDateField()
    {
        return 'lastModifiedDate';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getPositionField()
     */
    public function getPositionField()
    {
        return 'position';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::isPositionFieldNeedsAutoValue()
     */
    public function isPositionFieldNeedsAutoValue()
    {
        $result = false;
        $field = $this->getPositionField();
        if($this->containsField($field)){
            $fieldDefinition = $this->getFieldDefinition($field);
            $result = isset($fieldDefinition['editingDisabled']) && $fieldDefinition['editingDisabled'];
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getVirtualDeletionField()
     */
    public function getVirtualDeletionField()
    {
        return 'deleted';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getSinglePrimaryValue()
     */
    public function getSinglePrimaryValue(object $model)
    {
        return $this->hasMultiplePrimary() ? implode(Separator::PRIMARIES_DATA, $this->getPrimaryValues($model)) : $this->getPrimaryValue($model);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getMultipleUniques()
     */
    public function getMultipleUniques()
    {
        $definition = $this->getDefinition();
        return isset($definition['uniques']) ? $definition['uniques'] : array();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getActivationField()
     */
    public function getActivationField()
    {
        return 'active';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getStateField()
     */
    public function getStateField()
    {
        return 'state';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\model\ModelDefinition::getParentField()
     */
    public function getParentField() {
        return 'parentId';
    }
}