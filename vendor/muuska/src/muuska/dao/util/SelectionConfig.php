<?php
namespace muuska\dao\util;

use muuska\util\App;


class SelectionConfig extends DataConfig
{
	/**
	 * @var bool
	 */
	protected $langEnabled = true;
    /**
     * @var bool
     */
    protected $allLangsEnabled = false;
    
    /**
     * @var string
     */
    protected $lang;
    
    /**
     * @var bool
     */
    protected $onlyActive;
    
    /**
     * @var bool
     */
    protected $virtualDeletedEnabled = false;
    
    /**
     * @var bool
     */
    protected $dataCountingEnabled = false;
    
    /**
     * @var int
     */
    protected $start;
    
    /**
     * @var int
     */
    protected $limit;
    
    /**
     * @var MultipleSelectionAssociation[]
     */
    protected $multipleSelectionAssociations = array();
    
    /**
     * @var SortOption[]
     */
    protected $sortOptions = array();
    
    /**
     * @var string[]
     */
    protected $specificFields = array();
    
    /**
     * @var string[]
     */
    protected $excludedFields = array();
	
	/**
	 * @param string $lang
	 */
	public function __construct($lang = '') {
		$this->setLang($lang);
	}
	
	/**
	 * @param string $field
	 */
	public function addSpecificField($field) {
	    $this->specificFields[] = $field;
	}
	
	/**
	 * @param string $field
	 */
	public function addExcludedField($field) {
	    $this->excludedFields[] = $field;
	}
	
	/**
	 * @param string[] $fields
	 */
	public function addExcludedFields($fields) {
	    if(is_array($fields)){
	        foreach ($fields as $field) {
	            $this->addExcludedField($field);
	        }
	    }
	}
	
	/**
	 * @param string[] $fields
	 */
	public function addSpecificFields($fields) {
	    if(is_array($fields)){
	        foreach ($fields as $field) {
	            $this->addSpecificField($field);
	        }
	    }
	}
	
	/**
	 * @return bool
	 */
	public function hasSpecificFields(){
	    return !empty($this->specificFields);
	}
	
	/**
	 * @return bool
	 */
	public function hasExcludedFields(){
	    return !empty($this->excludedFields);
	}
	
	/*Sorting*/
	/**
	 * @param SortOption $sortOption
	 * @param string $key
	 */
	public function addSortOption(SortOption $sortOption, $key = '') {
	    $key = empty($key) ? $sortOption->getFieldName() : $key;
	    $this->sortOptions[$key] = $sortOption;
	}
	
	/**
	 * @param SortOption[] $sortOptions
	 */
	public function addSortOptions($sortOptions) {
	    if (is_array($sortOptions)) {
	        foreach ($sortOptions as $key => $sortOption) {
	            $finalKey = is_string($key) ? $key : '';
	            $this->addSortOption($sortOption, $finalKey);
	        }
	    }
	}
	
	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasSortOption($key) {
	    return (isset($this->sortOptions[$key]));
	}
	
	/**
	 * @return bool
	 */
	public function hasSortOptions() {
	    return !empty($this->sortOptions);
	}
	
	/**
	 * @param string $fieldName
	 * @param int $direction
	 * @param string $key
	 * @param bool $foreign
	 * @param string $externalField
	 * @return \muuska\dao\util\SortOption
	 */
	public function addSortOptionFromParams($fieldName, $direction = null, $key = '', $foreign = false, $externalField = null) {
		return $this->setSortOptionParams($fieldName, $direction, $key, $foreign, $externalField);
	}
	
	/**
	 * @param string $key
	 * @param bool $getNewIfNotExist
	 * @param bool $addToListIfNotExist
	 * @return SortOption
	 */
	public function getSortOptionByKey($key, $getNewIfNotExist = false, $addToListIfNotExist = false) {
		$object = null;
		if($this->hasSortOption($key)){
			$object = $this->sortOptions[$key];
		}else{
			$object = $getNewIfNotExist ? App::daos()->createSortOption($key, null) : $object;
			if($addToListIfNotExist && ($object != null)){
				$this->addSortOption($object, $key);
			}
		}
		return $object;
	}
	
	/**
	 * @param string $fieldName
	 * @param int $direction
	 * @param string $key
	 * @param bool $foreign
	 * @param string $externalField
	 * @return SortOption
	 */
	public function setSortOptionParams($fieldName, $direction = null, $key = '', $foreign = false, $externalField = null) {
		$key = empty($key) ? $fieldName : $key;
		$object = $this->getSortOptionByKey($key, true, true);
		$object->setFieldName($fieldName);
		$object->setDirection($direction);
		$object->setForeign($foreign);
		$object->setExternalField($externalField);
		return $object;
	}
	
	/**
	 * @param int $page
	 */
	public function setStartValueFromPage($page){
		$start = ((int)$page - 1) * $this->limit;
		$this->setStart($start);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\util\DataConfig::getAllFieldParameters()
	 */
	public function getAllFieldParameters()
	{
	    $result = parent::getAllFieldParameters();
	    $result['sort'] = $this->sortOptions;
	    return $result;
	}
	
	/*Multiple associations*/
	
	/**
	 * @param MultipleSelectionAssociation $multipleSelectionAssociation
	 */
	public function addMultipleSelectionAssociation(MultipleSelectionAssociation $multipleSelectionAssociation) {
	    $this->multipleSelectionAssociations[$multipleSelectionAssociation->getAssociationName()] = $multipleSelectionAssociation;
	}
	
	/**
	 * @param MultipleSelectionAssociation[] $multipleSelectionAssociations
	 */
	public function addMultipleSelectionAssociations($multipleSelectionAssociations) {
	    if (is_array($multipleSelectionAssociations)) {
	        foreach ($multipleSelectionAssociations as $multipleSelectionAssociation) {
	            $this->addMultipleSelectionAssociation($multipleSelectionAssociation);
	        }
	    }
	}
	
	/**
	 * @param string $associationName
	 * @return \muuska\dao\util\MultipleSelectionAssociation
	 */
	public function createMultipleSelectionAssociation($associationName) {
	    $association = App::daos()->createMultipleSelectionAssociation($associationName, $this->lang);
	    $this->addMultipleSelectionAssociation($association);
	    return $association;
	}
	
	/**
	 * @param string $associationName
	 * @return bool
	 */
	public function hasMultipleSelectionAssociation($associationName) {
	    return isset($this->multipleSelectionAssociations[$associationName]);
	}
	
	/**
	 * @return bool
	 */
	public function hasMultipleSelectionAssociations() {
	    return !empty($this->multipleSelectionAssociations);
	}
	
	/**
	 * @return bool
	 */
	public function hasLimit()
	{
	    return !empty($this->limit);
	}
	
    /**
     * @return boolean
     */
    public function isLangEnabled()
    {
        return $this->langEnabled;
    }

    /**
     * @return boolean
     */
    public function isAllLangsEnabled()
    {
        return $this->allLangsEnabled;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return boolean
     */
    public function isOnlyActive()
    {
        return $this->onlyActive;
    }

    /**
     * @return boolean
     */
    public function isVirtualDeletedEnabled()
    {
        return $this->virtualDeletedEnabled;
    }

    /**
     * @return boolean
     */
    public function isDataCountingEnabled()
    {
        return $this->dataCountingEnabled;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return MultipleSelectionAssociation[]
     */
    public function getMultipleSelectionAssociations()
    {
        return $this->multipleSelectionAssociations;
    }

    /**
     * @return SortOption[]
     */
    public function getSortOptions()
    {
        return $this->sortOptions;
    }

    /**
     * @return string[]
     */
    public function getSpecificFields()
    {
        return $this->specificFields;
    }

    /**
     * @return string[]
     */
    public function getExcludedFields()
    {
        return $this->excludedFields;
    }

    /**
     * @param boolean $langEnabled
     */
    public function setLangEnabled($langEnabled)
    {
        $this->langEnabled = $langEnabled;
    }

    /**
     * @param boolean $allLangsEnabled
     */
    public function setAllLangsEnabled($allLangsEnabled)
    {
        $this->allLangsEnabled = $allLangsEnabled;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @param boolean $onlyActive
     */
    public function setOnlyActive($onlyActive)
    {
        $this->onlyActive = $onlyActive;
    }

    /**
     * @param boolean $virtualDeletedEnabled
     */
    public function setVirtualDeletedEnabled($virtualDeletedEnabled)
    {
        $this->virtualDeletedEnabled = $virtualDeletedEnabled;
    }

    /**
     * @param boolean $dataCountingEnabled
     */
    public function setDataCountingEnabled($dataCountingEnabled)
    {
        $this->dataCountingEnabled = $dataCountingEnabled;
    }

    /**
     * @param int $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @param MultipleSelectionAssociation[] $multipleSelectionAssociations
     */
    public function setMultipleSelectionAssociations($multipleSelectionAssociations)
    {
        $this->multipleSelectionAssociations = array();
        $this->addMultipleSelectionAssociations($multipleSelectionAssociations);
    }

    /**
     * @param SortOption[] $sortOptions
     */
    public function setSortOptions($sortOptions)
    {
        $this->sortOptions = array();
        $this->addSortOptions($sortOptions);
    }

    /**
     * @param string[] $specificFields
     */
    public function setSpecificFields($specificFields)
    {
        $this->specificFields = array();
        $this->addSpecificFields($specificFields);
    }

    /**
     * @param string[] $excludedFields
     */
    public function setExcludedFields($excludedFields)
    {
        $this->excludedFields = array();
        $this->addExcludedFields($excludedFields);
    }
}
