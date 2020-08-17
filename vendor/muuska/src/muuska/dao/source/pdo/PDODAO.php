<?php
namespace muuska\dao\source\pdo;
use muuska\constants\operator\LogicalOperator;
use muuska\constants\operator\Operator;
use muuska\dao\AbstractDAO;
use muuska\dao\constants\DAOFunctionCode;
use muuska\dao\constants\JoinType;
use muuska\dao\constants\SortDirection;
use muuska\util\App;

class PDODAO extends AbstractDAO{
    const DEFAULT_PREFIX = 't';
    
    /**
     * @var PDODAOSource
     */
    protected $source;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param PDODAOSource $source
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, \muuska\dao\DAOFactory $daoFactory, PDODAOSource $source){
        parent::__construct($modelDefinition, $daoFactory, $source);
    }
    
    protected static $operatorList = array(
		Operator::EQUALS => '%s = %s',
		Operator::DIFFERENT => '%s <> %s',
		Operator::CONTAINS => array('field' => '%s LIKE %s', 'value' => '%%%s%%'),
		Operator::NOT_CONTAINS => array('field' => '%s NOT LIKE %s', 'value' => '%%%s%%'),
		Operator::START_WITH => array('field' => '%s LIKE %s', 'value' => '%s%%'),
		Operator::NOT_START_WITH => array('field' => '%s NOT LIKE %s', 'value' => '%s%%'),
		Operator::END_WITH => array('field' => '%s LIKE %s', 'value' => '%%%s'),
		Operator::NOT_END_WITH => array('field' => '%s NOT LIKE %s', 'value' => '%%%s'),
	);
	protected static $logicalOperatorList = array(
		LogicalOperator::AND_ => 'AND',
		LogicalOperator::OR_ => 'OR'
	);
	protected static $sortDirectionList = array(
		SortDirection::ASC => 'ASC',
		SortDirection::DESC => 'DESC'
	);
	protected static $joinTypeList = array(
		JoinType::INNER => 'INNER JOIN',
		JoinType::LEFT => 'LEFT JOIN',
		JoinType::RIGHT => 'RIGHT JOIN',
		JoinType::RIGHT => 'LEFT JOIN'
	);
     
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\AbstractDAO::addImplementation()
	 */
	protected function addImplementation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null) {
		$fieldsString ='(';
        $valuesString ='(';
        $first = true;
        $fields = $this->getFinalSaveFields($saveConfig);
        $simpleFields = array();
        $langFields = array();
        foreach ($fields as $field) {
            if($this->definition->isLangField($field)){
                $langFields[] = $field;
            }else{
                $simpleFields[] = $field;
                if ($first) {
                    $first = false;
                }else{
                    $fieldsString.=', ';
                    $valuesString.=', ';
                }
                $fieldsString.='`'.$field.'`';
                $valuesString.=':'.$field;
            }
        }
        $fieldsString.=')';
        $valuesString.=');';
        $sql = 'INSERT INTO '.'`'.$this->getTableName().'`'.$fieldsString.' VALUES '.$valuesString;
        $result = false;
        try {
            $query = $this->source->getPdo()->prepare($sql);
            foreach ($simpleFields as $field) {
                $this->addModelParam($query, $model, $field);
            }
            $result = $query->execute();
        } catch (\PDOException $e) {
            throw new \Exception(utf8_encode($e->getMessage()));
        }
        
        if($result && $this->definition->isAutoIncrement()){
            $this->definition->setPrimaryValue($model, $this->getLastId());
        }
        if($result && $this->isSaveConfigLangEnabled($saveConfig)){
            $result = $this->saveMultilingualFields($model, $langFields, $saveConfig, false);
        }
		return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\AbstractDAO::updateImplementation()
     */
    protected function updateImplementation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null) {
        return $this->doUpdate($model, $this->formatSaveConfigForUpdate($model, $saveConfig), false);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\AbstractDAO::updateMultipleRowsImplementation()
     */
    protected function updateMultipleRowsImplementation(object $model, \muuska\dao\util\SaveConfig $saveConfig = null){
        return $this->doUpdate($model, $saveConfig, true);
    }
    
    /**
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @param boolean $isMultipleUpdate
     * @return boolean
     */
    protected function doUpdate(object $model, \muuska\dao\util\SaveConfig $saveConfig = null, $isMultipleUpdate = false){
        $fields = $this->getFinalSaveFields($saveConfig);
        $simpleFields = array();
        $fieldsString ='';
        $first = true;
        $langFields = array();
        foreach ($fields as $field) {
            if($this->definition->isLangField($field)){
                $langFields[] = $field;
            }else{
                $simpleFields[] = $field;
                if ($first) {
                    $first = false;
                }else{
                    $fieldsString.=', ';
                }
                $fieldsString.=$field.' = :'.$field;
            }
        }
        $restrictionFields = array();
        if(($saveConfig !== null) && $saveConfig->hasRestrictions()){
            $restrictionFields = $saveConfig->getRestrictionFields();
        }
		$formatted = $this->formatAssociations($saveConfig);
        $restrictionsFormatted = $this->getRestrictionFromArray($restrictionFields, LogicalOperator::AND_, '_cond');
        $sql = 'UPDATE '.$this->getTableName(). ' '.self::DEFAULT_PREFIX . $formatted['associationJoin'] .' SET '.$fieldsString .
        (empty($restrictionsFormatted['condition']) ? '' : ' WHERE '.$restrictionsFormatted['condition']);
        
        $result = false;
        try {
            $query =$this->source->getPdo()->prepare($sql);
            
            foreach ($simpleFields as $field) {
                $this->addModelParam($query, $model, $field);
            }
            $this->addParamsFromAssociativeArray($query, $restrictionsFormatted['paramsToBind']);
            $result = $query->execute();
        } catch (\PDOException $e) {
            throw new \Exception(utf8_encode($e->getMessage()));
        }
		
        if($result &&  $this->isSaveConfigLangEnabled($saveConfig) && !empty($langFields)){
            if($isMultipleUpdate){
                $sqlSelect = 'SELECT ' . $this->definition->getPrimary() . ' FROM '.$this->getTableName().
                (empty($restrictionsFormatted['condition']) ? '' : ' WHERE '.$restrictionsFormatted['condition']);
                $query = $this->source->getPdo()->prepare($sqlSelect);
                $this->addParamsFromAssociativeArray($query, $restrictionsFormatted['paramsToBind']);
                $query->execute();
                $data = $query->fetch(\PDO::FETCH_ASSOC);
                foreach ($data as $row) {
                    $newModel = $this->definition->duplicateModel($model);
                    $this->definition->setPrimaryValue($newModel, $row[$this->definition->getPrimary()]);
                    $result &= $this->saveMultilingualFields($newModel, $langFields, $saveConfig, true);
                }
            }else{
                $result = $this->saveMultilingualFields($model, $langFields, $saveConfig, true);
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\AbstractDAO::deleteImplementation()
     */
    protected function deleteImplementation(object $model, \muuska\dao\util\DeleteConfig $deleteConfig = null) {
        if($deleteConfig === null){
            $deleteConfig = $this->createDeleteConfig();
        }
        $deleteConfig->createRestrictionFieldsFromArray($this->definition->getPrimaryValues($model));
        return $this->deleteMultipleRowsImplementation($deleteConfig);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\AbstractDAO::deleteMultipleRowsImplementation()
     */
    protected function deleteMultipleRowsImplementation(\muuska\dao\util\DeleteConfig $deleteConfig = null){
        $restrictionFields = array();
        if(($deleteConfig !== null) && $deleteConfig->hasRestrictions()){
            $restrictionFields = $deleteConfig->getRestrictionFields();
        }
		
        $formatted = $this->formatAssociations($deleteConfig);
        $restrictionsFormatted = $this->getRestrictionFromArray($restrictionFields, LogicalOperator::AND_, '', false);
        $sql = 'DELETE ' . self::DEFAULT_PREFIX . ' FROM '.$this->getTableName() . ' AS '.self::DEFAULT_PREFIX . $formatted['associationJoin'] . (empty($restrictionsFormatted['condition']) ? '' : ' WHERE '.$restrictionsFormatted['condition']);
        
        $result = false;
        try {
            $query = $this->source->getPdo()->prepare($sql);
            $this->addParamsFromAssociativeArray($query, $restrictionsFormatted['paramsToBind']);
            $result = $query->execute();
        } catch (\PDOException $e) {
            throw new \Exception(utf8_encode($e->getMessage()));
        }
        
        return $result;
    }
    
    /**
     * @param \muuska\dao\util\FieldParameter[] $fieldsParameters
     * @param \muuska\dao\util\DataConfig $dataConfig
     * @param \muuska\dao\DAO $mainDao
     */
    protected function formatFieldsParameters($fieldsParameters, \muuska\dao\util\DataConfig $dataConfig, \muuska\dao\DAO $mainDao = null){
		foreach ($fieldsParameters as $fieldKey => $fieldParameter) {
		    $this->formatFieldParameter($fieldParameter, $dataConfig, $fieldKey, $mainDao);
		}
	}
	
	/**
	 * @param \muuska\dao\util\FieldParameter $fieldParameter
	 * @param \muuska\dao\util\DataConfig $dataConfig
	 * @param string $fieldKey
	 * @param \muuska\dao\DAO $mainDao
	 */
	protected function formatFieldParameter(\muuska\dao\util\FieldParameter $fieldParameter, \muuska\dao\util\DataConfig $dataConfig, $fieldKey, \muuska\dao\DAO $mainDao = null){
		if($mainDao === null){
			$mainDao = $this;
		}
		if($fieldParameter->hasSubFields()){
		    $this->formatFieldsParameters($fieldParameter->getSubFields(), $dataConfig, $mainDao);
		}
		if($fieldParameter->hasDaoFunction()){
		    $this->formatDaoFunctionForAssociation($fieldParameter->getDaoFunction(), $dataConfig, $mainDao);
		}
		if($fieldParameter->hasValue()){
			if($fieldParameter->isFieldValueType()){
			    $this->formatFieldParameter($fieldParameter->getValue(), $dataConfig, '', $mainDao);
			}elseif($fieldParameter->isDaoFunctionValueType()){
			    $this->formatDaoFunctionForAssociation($fieldParameter->getValue(), $dataConfig, $mainDao);
			}
		}
		if($fieldParameter->isForeign()){
			$fieldName = $fieldParameter->getFieldName();
			$prefix = $this->getFieldParameterDefaultPrefix($fieldParameter);
			
			$association = null;
			$associationExist = false;
			if($fieldParameter->hasExtra('parentAssociation')){
				$parentAssociation = $fieldParameter->getExtra('parentAssociation');
				$associationExist = $parentAssociation->hasSubAssociation($fieldName);
				$association = $parentAssociation->getSubAssociationByKey($fieldName, true, true);
			}else{
			    $associationExist = $dataConfig->hasAssociation($fieldName);
			    $association = $dataConfig->getSelectionAssociationByKey($fieldName, true, true);
			}
			
			$fieldParameter->setExtra('prefix', $prefix);
			if(!$associationExist){
				$association->setRetrievingEnabled(false);
			}
			if($fieldParameter->hasJoinType()){
				$association->setJoinType($fieldParameter->getJoinType());
			}
			
			$foreignDao = $mainDao->getForeignDAO($association->getFieldName());
			if($foreignDao->getModelDefinition()->isLangField($fieldParameter->getExternalField())){
				$association->setLangEnabled(true);
				$fieldParameter->setExtra('prefix', $fieldParameter->getExtra('prefix').'_l');
				$association->setExtra('langPrefixSetted', true);
			}
			
			if($fieldParameter->hasSubExternalField()){
				$subExternalField = $fieldParameter->getSubExternalField();
				$subExternalField->setExtra('parentAssociation', $association);
				$subExternalFieldKey = $fieldKey . '___' . $fieldParameter->getExternalField();
				$this->formatFieldParameter($subExternalField, $dataConfig, $subExternalFieldKey, $foreignDao);
			}
		}
	}
	protected function formatDaoFunctionForAssociation(\muuska\dao\util\DAOFunction $daoFunction, \muuska\dao\util\DataConfig $dataConfig, \muuska\dao\DAO $mainDao = null){
		$functionParameters = $daoFunction->getParameters();
		foreach($functionParameters as $functionParameter){
			if($functionParameter->isFieldValueType()){
			    $this->formatFieldParameter($functionParameter->getValue(), $dataConfig, '', $mainDao);
			}elseif($functionParameter->isDaoFunctionValueType()){
			    $this->formatDaoFunctionForAssociation($functionParameter->getValue(), $dataConfig, $mainDao);
			}
		}
	}
	protected function formatAssociations(\muuska\dao\util\DataConfig $dataConfig = null){
		$result = array('associationsToGet' => array(), 'associationsLang' => array(), 'associationSelect' => '', 'associationJoin' => '');
		if(($dataConfig !== null) && $this->definition->hasForeignFields()){
		    $allFieldParameters = $dataConfig->getAllFieldParameters();
		    foreach ($allFieldParameters as $fieldParameters) {
		        if(!empty($fieldParameters)){
		            $this->formatFieldsParameters($fieldParameters, $dataConfig);
		        }
		    }
		    
		    $lang = $this->getLang($dataConfig);
			$associations = $dataConfig->getSelectionAssociations();
			foreach($associations as $association){
				/*$field = $association->getFieldName();*/
				$associationFormatted = $this->formatSelectionAssociation($association, $lang, $this);
				$result['associationSelect'] = $result['associationSelect'] . $associationFormatted['associationSelect'];
				$result['associationJoin'] = $result['associationJoin'] . $associationFormatted['associationJoin'];
				$result['associationsLang'] = array_merge($result['associationsLang'], $associationFormatted['associationsLang']);
				if(!empty($associationFormatted['associationsToGet'])){
					foreach($associationFormatted['associationsToGet'] as $associationsToGetKey => $associationsToGet){
						$result['associationsToGet'][$associationsToGetKey] = $associationsToGet;
					}
				}
			}
		}
		
		return $result;
	}
	protected function formatSelectionAssociation(\muuska\dao\util\SelectionAssociation $association, $lang, \muuska\dao\DAO $mainDao, $mainPrefix = null, $parentAssociationKey = null){
		$result = array('associationsToGet' => array(), 'associationsLang' => array(), 'associationSelect' => '', 'associationJoin' => '');
		$field = $association->getFieldName();
		$langEnabledTmp = $association->isLangEnabled();
		$allLangsEnabledTmp = $association->isAllLangsEnabled();
		$defaultPrefix = $this->getAssociationDefaultPrefix($association);
		if(empty($mainPrefix)){
			$mainPrefix = self::DEFAULT_PREFIX;
		}
		$foreignDao = $mainDao->getForeignDAO($field);
		if($foreignDao instanceof PDODAO){
		    if($association->isRetrievingEnabled()){
		        $result['associationSelect'] .= ', '.$foreignDao->getSelect($field, $langEnabledTmp, $allLangsEnabledTmp, $defaultPrefix, false, true);
		        $result['associationsToGet'][$defaultPrefix] = array('dao' =>$foreignDao, 'allLangsEnabled' => $allLangsEnabledTmp, 'langEnabled' => $langEnabledTmp, 'parentAssociationKey' => $parentAssociationKey, 'field' => $field);
		    }
		    if($langEnabledTmp && $foreignDao->definition->isMultilingual() && !$allLangsEnabledTmp){
		        $result['associationsLang'][] = $defaultPrefix;
		    }
		    $join = $association->hasJoinType() ? $association->getJoinType() : JoinType::LEFT;
		    $fieldDefinition = $mainDao->definition->getFieldDefinition($field);
		    $referenceField = isset($fieldDefinition['referenceField']) ? $fieldDefinition['referenceField'] : $foreignDao->definition->getPrimary();
		    $result['associationJoin'] .= ' '.$foreignDao->getTableSelect($lang, $langEnabledTmp, $allLangsEnabledTmp, $defaultPrefix, true, $field, $referenceField, $mainPrefix, $join);
		    
		    /*Sub association*/
		    if($association->hasSubAssociations()){
		        $subAssociations = $association->getSubAssociations();
		        foreach($subAssociations as $subAssociation){
		            $subAssociation->setExtra('parentAssociation', $association);
		            $subAssociationFormatted = $this->formatSelectionAssociation($subAssociation, $lang, $foreignDao, $defaultPrefix, $defaultPrefix);
		            $result['associationSelect'] = $result['associationSelect'] . $subAssociationFormatted['associationSelect'];
		            $result['associationJoin'] = $result['associationJoin'] . $subAssociationFormatted['associationJoin'];
		            $result['associationsLang'] = array_merge($result['associationsLang'], $subAssociationFormatted['associationsLang']);
		            if(!empty($subAssociationFormatted['associationsToGet'])){
		                if(empty($result['associationsToGet'])){
		                    $result['associationsToGet'][$defaultPrefix] = array('retrievingDisabled' => true, 'dao' =>$foreignDao, 'allLangsEnabled' =>$allLangsEnabledTmp, 'langEnabled' => $langEnabledTmp, 'parentAssociationKey' => $parentAssociationKey, 'field' => $field);
		                }
		                if(!empty($subAssociationFormatted['associationsToGet'])){
		                    foreach($subAssociationFormatted['associationsToGet'] as $associationsToGetKey => $associationsToGet){
		                        $result['associationsToGet'][$associationsToGetKey] = $associationsToGet;
		                    }
		                }
		            }
		        }
		    }
		}
		
		return $result;
	}
	protected function getAssociationDefaultPrefix(\muuska\dao\util\SelectionAssociation $association){
		$prefix = $association->getFieldName();
		if($association->hasExtra('parentAssociation')){
			$prefix .= '__'.$this->getAssociationDefaultPrefix($association->getExtra('parentAssociation'));
		}
		return $prefix;
	}
	protected function getFieldParameterDefaultPrefix(\muuska\dao\util\FieldParameter $fieldParameter){
		$prefix = $fieldParameter->getFieldName();
		if($fieldParameter->hasExtra('parentAssociation')){
			$prefix .= '__'.$this->getFieldParameterDefaultPrefix($fieldParameter->getExtra('parentAssociation'));
		}
		return $prefix;
	}
    
	protected function getDataImplementation(\muuska\dao\util\SelectionConfig $selectionConfig = null) {
		$lang = $this->getLang($selectionConfig);
		$logicalOperator = null;
		$langEnabled = true;
		$allLangsEnabled = false;
		$restrictionFields = array();
		$sortOptions = array();
		$start = 0;
		$limit = 0;
		if($selectionConfig != null){
			$langEnabled = $selectionConfig->isLangEnabled();
			$allLangsEnabled = $selectionConfig->isAllLangsEnabled();
			$logicalOperator = $selectionConfig->getLogicalOperator();
			$restrictionFields = $selectionConfig->getRestrictionFields();
			$start = $selectionConfig->getStart();
			$limit = $selectionConfig->getLimit();
			$sortOptions = $selectionConfig->getSortOptions();
		}
		$formatted = $this->formatAssociations($selectionConfig);
        $restrictionsFormatted = $this->getRestrictionFromArray($restrictionFields, $logicalOperator);
		$sharedSql = $this->getTableSelect($lang, $langEnabled, $allLangsEnabled) . $formatted['associationJoin'] . (empty($restrictionsFormatted['condition'])?'':' WHERE '.$restrictionsFormatted['condition']);
        $sortOptionsFormatted = $this->formatSortOptions($sortOptions);
		$sql = 'SELECT ' . $this->getSelect($lang, $langEnabled, $allLangsEnabled) . $formatted['associationSelect'] .$sharedSql.
		$sortOptionsFormatted['condition'] . $this->getLimitString($start, $limit);
		
		$result = null;
		try {
		    $query = $this->source->getPdo()->prepare($sql);
		    
		    $this->addParamsFromAssociativeArray($query, $restrictionsFormatted['paramsToBind']);
		    $this->addParamsFromAssociativeArray($query, $sortOptionsFormatted['paramsToBind']);
		    $this->addLangParam($query, $lang, $langEnabled, $allLangsEnabled, $formatted['associationsLang']);
		    $query->execute();
		} catch (\PDOException $e) {
		    throw new \Exception(utf8_encode($e->getMessage()));
		}
		
		$result = $this->getAllAsObjectFromQuery($query, $langEnabled, $allLangsEnabled, $formatted['associationsToGet'], $lang);
		if(($selectionConfig !== null) && $selectionConfig->isDataCountingEnabled()){
			$params = array('sharedSql'=>$sharedSql, 'formatted'=>$formatted, 'restrictionsFormatted'=>$restrictionsFormatted);
			$total = $this->getByFieldsCountFromFormatted($restrictionFields, $logicalOperator, $lang, $langEnabled, $allLangsEnabled, $params);
			$result->setTotalWithoutLimit($total);
		}
		return $result;
    }
	
    protected function getDataTotalImplementation(\muuska\dao\util\SelectionConfig $selectionConfig = null){
		$lang = $this->getLang($selectionConfig);
		$logicalOperator = null;
		$langEnabled = true;
		$allLangsEnabled = false;
		$restrictionFields = array();
		if($selectionConfig !== null){
			$langEnabled = $selectionConfig->isLangEnabled();
			$allLangsEnabled = $selectionConfig->isAllLangsEnabled();
			$logicalOperator = $selectionConfig->getLogicalOperator();
			$restrictionFields = $selectionConfig->getRestrictionFields();
		}
		$formatted = $this->formatAssociations($selectionConfig);
        $restrictionsFormatted = $this->getRestrictionFromArray($restrictionFields, $logicalOperator);
		$sharedSql = $this->getTableSelect($lang, $langEnabled, $allLangsEnabled) . $formatted['associationJoin'] . (empty($restrictionsFormatted['condition'])?'':' WHERE '.$restrictionsFormatted['condition']);
		$params = array('sharedSql'=>$sharedSql, 'formatted'=>$formatted, 'restrictionsFormatted'=>$restrictionsFormatted);
		return $this->getByFieldsCountFromFormatted($restrictionFields, $logicalOperator, $lang, $langEnabled, $allLangsEnabled, $params);
	}
	
	protected function getByFieldsCountFromFormatted($restrictionFields, $logicalOperator, $lang, $langEnabled, $allLangsEnabled, $params){
		$primaries = $this->definition->getPrimaries();
		$first = true;
		$sql = 'SELECT COUNT(DISTINCT ';
		foreach($primaries as $primary){
			if(!$first){
				$sql.=', ';
			}
			$sql.=self::DEFAULT_PREFIX.'.`'. $this->bqSQL($primary).'`';
			$first = false;
		}
		$sql .= ') AS number '.$params['sharedSql'];
		$result = 0;
		try {
		    $query =$this->source->getPdo()->prepare($sql);
		    $this->addParamsFromAssociativeArray($query, $params['restrictionsFormatted']['paramsToBind']);
		    $this->addLangParam($query, $lang, $langEnabled, $allLangsEnabled, $params['formatted']['associationsLang']);
		    $query->execute();
		    $data = $query->fetch(\PDO::FETCH_ASSOC);
		    $result = (int)$data['number'];
		} catch (\PDOException $e) {
		    throw new \Exception(utf8_encode($e->getMessage()));
		}
		
		return $result;
	}
	protected function getTableSelect($lang, $langEnabled, $allLangsEnabled, $prefix = '', $foreign = false, $foreignField = '', $referenceField = '', $parentPrefix = '', $join='')
    {
		$prefix = empty($prefix) ? self::DEFAULT_PREFIX : $prefix;
		$sql = '';
		$protectedPrefix = $this->bqSQL($prefix);
		$tableSql = ' `'.$this->bqSQL($this->getTableName()) .'` `'. $protectedPrefix.'` ';
		if($foreign){
			$sql.=' '.self::$joinTypeList[$join].' '.$tableSql.
				' ON (`'. $protectedPrefix.'`.`'. $this->bqSQL($referenceField) .'` = `'. $parentPrefix.'`.`'. $this->bqSQL($foreignField) .'`)';
		}else{
			$sql.=' FROM '.$tableSql;
		}
		$sql .= $this->getLangJoin($prefix, $lang, $langEnabled, $allLangsEnabled, $foreign);
		return $sql;
    }
	protected function getSelect($lang, $langEnabled, $allLangsEnabled, $prefix = '', $useMultipleSelect = true, $foreign = false)
    {
		$prefix = empty($prefix) ? self::DEFAULT_PREFIX : $prefix;
		$string = $useMultipleSelect ? $this->bqSQL($prefix).'.*' : $this->formatSelectFields($this->definition->getSimpleFields(), $prefix , $foreign);
		$addSeparator = true;
		if(empty($string)){
			$addSeparator = false;
		}
		$string .= $this->getLangSelect($prefix, $lang, $langEnabled, $allLangsEnabled, $useMultipleSelect, $foreign, $addSeparator);
		return $string;
    }
	
	protected function formatSelectFields($fields, $prefix, $foreign = false, $lang = false)
    {
		$string = '';
		$first = true;
		$protectedPrefix = $this->bqSQL($prefix);
		if($this->definition->isAutoIncrement() && !$foreign){
		    $fields[] = $this->definition->getPrimary();
		}
        foreach ($fields as $field) {
            if ($first) {
				$first = false;
			}else{
				$string.=', ';
			}
			$string .= '`'. $protectedPrefix .($lang ? '_l':'') .'`.`'.$this->bqSQL($field).'` ' .
				($foreign ? ' AS `'.$protectedPrefix.$field.'`' : '');
        }
        return $string;
    }
	
	protected function getLangJoin($prefix, $lang, $langEnabled, $allLangsEnabled, $foreign = false)
    {
		$join = ' ';
		if($this->definition->isMultilingual() && $langEnabled && !$this->definition->hasMultiplePrimary()){
			$protectedPrefix = $this->bqSQL($prefix);
			$langPrefix = $protectedPrefix.'_l';
			$join .= ' LEFT JOIN `'.$this->bqSQL($this->getLangTableName()).'` `'.$langPrefix.'` ON ((`'.$langPrefix.'`.`'.$this->bqSQL($this->getPrimaryFieldNameForLang()) .'` = `'.
	 			$protectedPrefix.'`.`' . $this->definition->getPrimary() . '`) '. ($allLangsEnabled ? '' : ' AND (`'.$langPrefix.'`.lang = :'.$prefix.'lang)').') ';
		}
        return $join;
    }
	protected function getLangSelect($prefix, $lang, $langEnabled, $allLangsEnabled, $useMultipleSelect = true, $foreign = false, $addSeparator = true)
    {
		$sql = ' ';
		if($this->definition->isMultilingual() && $langEnabled && !$this->definition->hasMultiplePrimary()){
			$langFields = $this->definition->getLangFields();
			$langFields[]='lang';
			if($addSeparator){
				$sql .= ', ';
			}
			$sql .= ($useMultipleSelect ? $this->bqSQL($prefix.'_l').'.*' : $this->formatSelectFields($langFields, $prefix, $foreign, true));
		}
        return $sql;
    }
	protected function addLangParam($query, $lang, $langEnabled, $allLangsEnabled, $others = array())
    {
        if($langEnabled && $this->definition->isMultilingual() && !$allLangsEnabled && !$this->definition->hasMultiplePrimary()){
			$others[] = self::DEFAULT_PREFIX;
		}
		foreach($others as $prefix){
			$query->bindValue(':'.$prefix.'lang', $lang);
		}
    }
    
    protected function getAllAsObjectFromQuery($query, $langEnabled = true, $allLangsEnabled = false, $associationsToGet = array(), $defaultLang = ''){
        $result = array();
		$ids = array();
		$i = 0;
		$primary = $this->definition->getPrimary();
		$currentDef = array(
			'' => array('dao'=>$this, 'allLangsEnabled' =>$allLangsEnabled, 'langEnabled' =>$langEnabled)
		);
		$objectsToget = array_merge($currentDef, $associationsToGet);
		while ($data = $query->fetch(\PDO::FETCH_ASSOC)){
			$id = null;
			if(isset($data[$primary])){
			    $id = $data[$primary];
			}
			$allLangsEnabled = false;
			$increment = false;
			$mainModels = array();
			foreach($objectsToget as $prefix => $params){
				$parentPrefix = isset($params['parentAssociationKey']) ? $params['parentAssociationKey'] : '';
				$mainModelPrefix = empty($parentPrefix) ? '' : $parentPrefix;
				$associationField = isset($params['field']) ? $params['field'] : $prefix;
				
				if($params['dao']->definition->isMultilingual() && $params['allLangsEnabled'] && isset($ids[$id])){
					$model = null;
					$modelDefinition = $params['dao']->definition;
					$defaultMainModel = $result[$ids[$id]];
					if(empty($prefix)){
						$model = $defaultMainModel;
					}elseif(isset($mainModels[$mainModelPrefix])){
						$model = $mainModels[$mainModelPrefix]['definition']->getAssociated($mainModels[$mainModelPrefix]['model'], $prefix);
						$modelDefinition = $mainModels[$mainModelPrefix]['definition']->getAssociatedModelDefinition($prefix);
					}elseif(empty($parentPrefix)){
					    $model = $params['dao']->definition->getAssociated($defaultMainModel, $prefix);
						$modelDefinition = $params['dao']->definition->getAssociatedModelDefinition($prefix);
					}
					if($model !== null){
					    $langFields = $modelDefinition->getLangFields();
						foreach($langFields as $field){
							$currentLang = $data[$prefix.'lang'];
							$modelDefinition->setPropertyValueByLang($model, $field, $data[$prefix.$field], $currentLang);
							if($params['allLangsEnabled'] && ($currentLang == $defaultLang)){
								$modelDefinition->setPropertyValue($model, $field, $data[$prefix.$field]);
							}
						}
					}
				}else{
				    $model = $params['dao']->createModel();
					if(!empty($prefix)){
					    $primaryFieldValueKey = $parentPrefix . $associationField;
						if(isset($data[$primaryFieldValueKey])){
						    /*$data[$prefix.$params['dao']->definition->getPrimary()] = $data[$primaryFieldValueKey];*/
						    $params['dao']->definition->setPrimaryValue($model, $data[$primaryFieldValueKey]);
						}
					}elseif(!$params['dao']->definition->hasMultiplePrimary()){
					    $params['dao']->definition->setPrimaryValue($model, $data[$prefix.$params['dao']->definition->getPrimary()]);
					}
					$lang = ($params['dao']->definition->isMultilingual() && $params['langEnabled'] && isset($data[$prefix.'lang'])) ? $data[$prefix.'lang'] : '';
					$this->hydrate($params['dao']->definition, $model, $data, $lang, $params['allLangsEnabled'], $prefix);
					/*Set default fields lang*/
					if($params['allLangsEnabled'] && ($lang == $defaultLang)){
					    $langFields = $params['dao']->definition->getLangFields();
						foreach($langFields as $field){
						    $params['dao']->definition->setPropertyValue($model, $field, $data[$prefix.$field]);
						}
					}
					
					$mainModels[$prefix]['model'] = $model;
					$mainModels[$prefix]['definition'] = $params['dao']->definition;
					if(empty($prefix)){
						$result[] = $model;
						$increment = true;
					}elseif(isset($mainModels[$mainModelPrefix])){
					    $mainModels[$mainModelPrefix]['definition']->setAssociatedModel($mainModels[$mainModelPrefix]['model'], $associationField, $model);
					}
				}
				$allLangsEnabled = ($allLangsEnabled || $params['allLangsEnabled']);
			}
			if($increment && $allLangsEnabled){
				$ids[$id] = $i;
				$i++;
			}
        }
        $query->closeCursor();
		
        $listResult = App::daos()->createDAOListResult($this->definition, $result);
        return $listResult;
    }
    
    protected function hydrate(\muuska\model\ModelDefinition $modelDefinition, $model, $data, $lang = '', $allLangEnabled = false, $prefix = '')
    {
        if(is_array($data)){
            $stringTools = App::getStringTools();
            foreach ($data as $key => $value) {
                if(empty($prefix) || (strpos($key, $prefix) === 0)){
                    $field = $stringTools->removePrefix($key, $prefix);
                    if ($modelDefinition->containsField($field)){
                        if($modelDefinition->isLangField($field) && $allLangEnabled && !empty($lang)){
                            $modelDefinition->setPropertyValueByLang($model, $field, $value, $lang);
                        }else{
                            $modelDefinition->setPropertyValue($model, $field, $value);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * @param \muuska\dao\util\SortOption[] $sortOptions
     * @throws \Exception
     * @return array
     */
    protected function formatSortOptions($sortOptions){
		$paramsToBind = array();
		$sql = '';
		$first = true;
		foreach ($sortOptions as $sortKey => $sortOption) {
			if ($first) {
				$first = false;
			}else{
				$sql.=', ';
			}
			$direction = $sortOption->getDirection();
			$direction = empty($direction) ? SortDirection::ASC : $direction;
			if(!isset(self::$sortDirectionList[$direction])){
				throw new \Exception('Invalid sort direction');
			}else{
				$fieldSql = $this->getModelFieldNameAndPrefixFromFieldParameter($sortOption, true);
				
				if($sortOption->hasDaoFunction()){
					$functionResult = $this->getDaoFunctionSQL($sortKey, $sortOption->getDaoFunction(), 'sort', true, 1);
					$fieldSql = $functionResult['condition'];
					if(!empty($functionResult['paramsToBind'])){
						$paramsToBind = array_merge($paramsToBind, $functionResult['paramsToBind']);
					}
				}
				$sql .= $fieldSql.' '.self::$sortDirectionList[$direction].' ';
			}
        }
		$sql = empty($sql) ? $sql : ' ORDER BY ' . $sql;
		
		return array('condition' => $sql, 'paramsToBind' => $paramsToBind);
    }
    
    protected function getLimitString($start, $limit){
        return ($limit>0) ? ' LIMIT '.(int)$start.', '.(int)$limit : '';
    }
    
    protected function addModelParam($query, object $model, $field, $lang = null) {
        $value = empty($lang) ? $this->definition->getPropertyValue($model, $field) : $this->definition->getPropertyValueByLang($model, $field, $lang);
		$query->bindValue(':'.$field, $value);
    }
    
    /**
     * @param \muuska\dao\util\FieldRestriction[] $restrictionFields
     * @param int $logicalOperator
     * @param string $valueSuffix
     * @param boolean $usePrefix
     * @return array
     */
    protected function getRestrictionFromArray($restrictionFields, $logicalOperator = null, $valueSuffix = '', $usePrefix = true) {
		$result = array('condition' => '', 'paramsToBind' => array());
		$first = true;
		$logicalOperator = empty($logicalOperator) ? LogicalOperator::AND_ : $logicalOperator;
        foreach ($restrictionFields as $fieldKey => $restrictionField) {
			if ($first) {
				$first = false;
			}else{
				$result['condition'] .= ' ' .(isset(self::$logicalOperatorList[$logicalOperator]) ? self::$logicalOperatorList[$logicalOperator] : 'AND').' ';
			}
			if($restrictionField->hasSubFields()){
				$subResult = $this->getRestrictionFromArray($restrictionField->getSubFields(), $restrictionField->getLogicalOperator(), $fieldKey.$valueSuffix, $usePrefix);
				if(!empty($subResult['condition'])){
					$result['condition'] .= '(' . $subResult['condition'] . ')';
				}
				if(!empty($subResult['paramsToBind'])){
					$result['paramsToBind'] = array_merge($result['paramsToBind'], $subResult['paramsToBind']);
				}
			}else{
				$operatorResult = $this->getOperatorQuery($fieldKey, $restrictionField, $valueSuffix, $usePrefix, $usePrefix);
				if(!empty($operatorResult['condition'])){
					$result['condition'] .= $operatorResult['condition'];
				}
				if(!empty($operatorResult['paramsToBind'])){
					$result['paramsToBind'] = array_merge($result['paramsToBind'], $operatorResult['paramsToBind']);
				}
			}
        }
        return $result;
    }
	
   protected function getModelFieldNameAndPrefixFromFieldParameter(\muuska\dao\util\FieldParameter $fieldParameter, $usePrefix = true){
		$result = '';
		if($fieldParameter->hasSubExternalField()){
			$result = $this->getModelFieldNameAndPrefixFromFieldParameter($fieldParameter->getSubExternalField(), $usePrefix);
		}else{
			$prefix = '';
			if($fieldParameter->hasExtra('prefix')){
			    $prefix = $fieldParameter->getExtra('prefix');
			}else{
				$prefix = self::DEFAULT_PREFIX .($this->definition->isLangField($fieldParameter->getFieldName()) ? '_l' :'');
			}
			$modelField = $fieldParameter->isForeign() ? $fieldParameter->getExternalField() : $fieldParameter->getFieldName();
			$protectedPrefix = $this->bqSQL($prefix);
			$result = ($usePrefix ? '`'.$protectedPrefix.'`.' : '').'`'.$this->bqSQL($modelField).'`';
		}
		return $result;
	}
	
	protected function getOperatorQuery($fieldKey, \muuska\dao\util\FieldRestriction $restrictionField, $valueSuffix, $usePrefix) {
		$paramsToBind = array();
		$sql = '(';
		
		$operator = $restrictionField->getOperator();
		$operator = empty($operator) ? Operator::EQUALS : $operator;
		$value = $restrictionField->getValue();
		
		$isFieldValueType = $restrictionField->isFieldValueType();
		$isDaoFunctionValueType = $restrictionField->isDaoFunctionValueType();
		
		$fieldSql = $this->getModelFieldNameAndPrefixFromFieldParameter($restrictionField, $usePrefix);
		if($restrictionField->hasDaoFunction()){
			$functionResult = $this->getDaoFunctionSQL($fieldKey, $restrictionField->getDaoFunction(), $valueSuffix, $usePrefix, 1);
			$fieldSql = $functionResult['condition'];
			if(!empty($functionResult['paramsToBind'])){
				$paramsToBind = array_merge($paramsToBind, $functionResult['paramsToBind']);
			}
		}
		
		if($value === null){
		    $sql .= ($operator == Operator::EQUALS) ? '((' . $fieldSql.' IS NULL) OR ('.$fieldSql.'=""))' : '((' . $fieldSql.' IS NOT NULL) OR ('.$fieldSql.'<>""))';
		}elseif(isset(self::$operatorList[$operator])){
			if($isFieldValueType){
				$valueSql = $this->getFieldParameterSQLValue($value, $usePrefix);
			}elseif($isDaoFunctionValueType){
				$functionResultTmp = $this->getDaoFunctionSQL($fieldKey, $value, $valueSuffix, $usePrefix, 1);
				$valueSql = $functionResultTmp['condition'];
				if(!empty($functionResultTmp['paramsToBind'])){
					$paramsToBind = array_merge($paramsToBind, $functionResultTmp['paramsToBind']);
				}
			}else{
				$valueSql = ':'.$fieldKey.$valueSuffix;
			}
			$formatter = is_array(self::$operatorList[$operator]) ? self::$operatorList[$operator]['field'] : self::$operatorList[$operator];
			$sql .= sprintf($formatter, $fieldSql, $valueSql);
			if(!$isFieldValueType && !$isDaoFunctionValueType){
				$formattedValue = $value;
				if(is_array(self::$operatorList[$operator]) && isset(self::$operatorList[$operator]['value'])){
					$formattedValue = sprintf(self::$operatorList[$operator]['value'], $value);
				}
				$paramsToBind[$valueSql] = $formattedValue;
			}
		}elseif(($operator == Operator::BETWEEN) || ($operator == Operator::NOT_BETWEEN)){
			$i = 1;
			$values = is_array($value) ? $value : array('' => $value);
			$betweenPrefix = ($operator == Operator::NOT_BETWEEN) ? 'NOT ' : '';
			foreach($values as $key => $val){
				$valueSql = ':' . $fieldKey . $key . $valueSuffix;
				$sql .= ($i==1) ? ($fieldSql. $betweenPrefix . ' BETWEEN ' . $valueSql) : (' AND ' . $valueSql);
				$paramsToBind[$valueSql] = $val;
				$i++;
				if($i==3){
					break;
				}
			}
		}elseif(($operator == Operator::IN_LIST)||($operator == Operator::NOT_IN_LIST)){
			if(!empty($value)){
				$sql .= $fieldSql.' '.(($operator == Operator::IN_LIST) ? 'IN' : 'NOT IN').'(';
				$joinValue = '';
				if($isDaoFunctionValueType){
					$functionResultTmp = $this->getDaoFunctionSQL($fieldKey, $value, $valueSuffix, $usePrefix, 1);
					$joinValue = $functionResultTmp['condition'];
					if(!empty($functionResultTmp['paramsToBind'])){
						$paramsToBind = array_merge($paramsToBind, $functionResultTmp['paramsToBind']);
					}
				}else{
					$values = is_array($value) ? $value : array('' => $value);
					$first = true;
					foreach ($values as $key => $val) {
						if ($first) {
							$first = false;
						}else{
							$joinValue.=',';
						}
						$valueSql = ':' . $fieldKey . $key . $valueSuffix;
						$joinValue .= $valueSql;
						$paramsToBind[$valueSql] = $val;
					}
				}
				$sql .=$joinValue.')';
			}else{
				$sql .= ($operator == Operator::IN_LIST) ? '(1=0)' : '(1=1)';
			}
		}
		$sql .= ')';
		return array('condition' => $sql, 'paramsToBind' => $paramsToBind);
    }
    protected function getFieldParameterSQLValue(\muuska\dao\util\FieldParameter  $fieldParameter, $usePrefix = false) {
		$valueSql = $this->getModelFieldNameAndPrefixFromFieldParameter($fieldParameter, $usePrefix);
		return $valueSql;
	}
	protected function getDaoFunctionSQL($fieldKey, \muuska\dao\util\DAOFunction $daoFunction, $valueSuffix, $usePrefix, $depth = 0, $position = 0, $parentFunctionId = '') {
		$sql = '';
		$paramsToBind = array();
		$depth = (int)$depth;
		$position = (int)$position;
		
		$availableFunctionNames = array();
		$arithmeticCodes = array(
			DAOFunctionCode::ADD => '+',
			DAOFunctionCode::SUBTRACT => '-',
			DAOFunctionCode::MULTIPLY => '*',
			DAOFunctionCode::DIVIDE => '/',
		);
		
		$code = strtoupper($daoFunction->getCode());
		$functionName = isset($availableFunctionNames[$code]) ? $availableFunctionNames[$code] : $this->protectString($code);
		$parameterSeparator = ',';
		$functionBegin = '';
		$functionEnd = ')';
		
		if(isset($arithmeticCodes[$code])){
			$parameterSeparator = $arithmeticCodes[$code];
			$functionBegin = '(';
		}else{
			$functionBegin = $functionName.'(';
		}
		
		$currentFunctionId = 'function_'.$code . '_'.$depth . '_'.$position;
		$functionId = $parentFunctionId . $currentFunctionId;
		
		$parametersSql = '';
		$functionParameters = $daoFunction->getParameters();
		$i = 0;
		foreach($functionParameters as $functionParameter){
			if($i > 0){
				$parametersSql.=$parameterSeparator;
			}
			if($functionParameter->isFieldValueType()){
				$parametersSql .= $this->getFieldParameterSQLValue($functionParameter->getValue(), $usePrefix);
			}elseif($functionParameter->isDaoFunctionValueType()){
				$functionResultTmp = $this->getDaoFunctionSQL($fieldKey, $functionParameter->getValue(), $valueSuffix, $usePrefix, ($depth + 1), $i, $functionId);
				$parametersSql .= $functionResultTmp['condition'];
				if(!empty($functionResultTmp['paramsToBind'])){
					$paramsToBind = array_merge($paramsToBind, $functionResultTmp['paramsToBind']);
				}
			}else{
				$valueSql = ':'.$fieldKey.$valueSuffix.$functionId.'_'.$i;
				$parametersSql .= $valueSql;
				$paramsToBind[$valueSql] = $functionParameter->getValue();
			}
			$i++;
		}
		$sql = $functionBegin . $parametersSql . $functionEnd;
		return array('condition' => $sql, 'paramsToBind' => $paramsToBind);
	}
	protected  function addParamsFromAssociativeArray($query, $params) {
		foreach ($params as $fieldKey => $value) {
			$query->bindValue($fieldKey, $value);
		}
    }
    
    protected function saveMultilingualFields($model, $langFields, \muuska\dao\util\SaveConfig $saveConfig = null, $update = false)
    {
        $languages = $this->getLanguages($saveConfig);
		$result = true;
		if($this->definition->isMultilingual() && !$this->definition->hasMultiplePrimary() && !empty($langFields)){
		    $idObject = $this->definition->getPrimaryValue($model);
			if($update){
				$sqlInit = $this->getLangUpdateSqlInit($langFields);
			}else{
				$addSqlInit= $this->getLangAddSqlInit($langFields);
			}
			foreach ($languages as $langObject){
			    $lang = $langObject->getUniqueCode();
				if($update && $this->isObjectSavedForLang($idObject, $lang)){
					$sql = $sqlInit;
				}else{
					if(!isset($addSqlInit)){
						$addSqlInit = $this->getLangAddSqlInit($langFields);
					}
					$sql = $addSqlInit;
				}
				try {
				    $query=$this->source->getPdo()->prepare($sql);
				    $query->bindValue(':' . $this->getPrimaryFieldNameForLang(), $idObject);
				    $query->bindValue(':lang', $lang);
				    foreach ($langFields as $field){
				        $this->addModelParam($query, $model, $field, $lang);
				    }
				    $result = ($result && (bool)$query->execute());
				} catch (\PDOException $e) {
				    throw new \Exception(utf8_encode($e->getMessage()));
				}
			}
		}
		return $result;
    }
    
    protected function getLangAddSqlInit($langFields)
    {
        $sqlInit='INSERT INTO ' . $this->getLangTableName().' (' .$this->getPrimaryFieldNameForLang() . ', lang, ' .
      	implode(',', $langFields) . ') VALUES(:' . $this->getPrimaryFieldNameForLang() . ', :lang, :' . implode(',:', $langFields).')';
      	return $sqlInit;
    }
    
    protected function getLangUpdateSqlInit($langFields)
    {
        $sqlInit= 'UPDATE '.$this->getLangTableName().' SET ';
    	$first= true;
    	foreach ($langFields as $field){
    		if(!$first){
    			$sqlInit.=', ';
    		}
    		$sqlInit.=$field.' = :'.$field;
    		$first = false;
    	}
    	$sqlInit.=' WHERE ('.$this->getPrimaryFieldNameForLang() . ' = :' . $this->getPrimaryFieldNameForLang() . ') AND (lang = :lang)';
    	return $sqlInit;
    }
    
    protected function isObjectSavedForLang($idObject, $lang)
    {
        $sql = 'SELECT COUNT(*) AS number FROM '.$this->getTableName().
    	'_lang WHERE ('.$this->getPrimaryFieldNameForLang().' = :idObject) AND (lang = :lang)';
    	$query=$this->source->getPdo()->prepare($sql);
    	$query->bindValue(':idObject', $idObject);
    	$query->bindValue(':lang', $lang);
    	$query->execute();
    	$data = $query->fetch(\PDO::FETCH_OBJ);
    	return ((int)$data->number > 0);
    }
    
    protected function bqSQL($string)
    {
        return str_replace('`', '\`', $this->protectString($string));
    }
	
    protected function getPrimaryFieldNameForLang()
    {
    	return $this->source->getPrimaryFieldNameForLang($this->definition);
    }
    
    public function getLastId(){
        return $this->source->getLastInsertId();
    }
    
    protected function getTableName()
    {
        return $this->source->getTableName($this->definition);
    }
    
    protected function getLangTableName()
    {
        return $this->source->getLangTableName($this->definition);
    }
    
    /**
     * @param string $table
     * @return bool
     */
    protected function truncateTable($table)
    {
        $result = false;
        try {
            $result = $this->source->getPdo()->prepare('TRUNCATE TABLE :name' . $table)->execute(array(':name' => $table));
        } catch (\PDOException $e) {
            throw new \Exception(utf8_encode($e->getMessage()));
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\dao\AbstractDAO::clearDataImplementation()
     */
    protected function clearDataImplementation()
    {
        if($this->definition->isMultilingual()){
            $this->truncateTable($this->getLangTableName());
        }
        return $this->truncateTable($this->getTableName());
    }
}