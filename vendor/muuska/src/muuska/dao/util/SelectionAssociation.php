<?php
namespace muuska\dao\util;

use muuska\util\AbstractExtraDataProvider;
use muuska\util\App;

class SelectionAssociation extends AbstractExtraDataProvider
{
    /**
     * @var string
     */
    protected $fieldName;
    
    /**
     * @var int
     */
    protected $joinType;
    
    /**
     * @var bool
     */
    protected $retrievingEnabled;
    
	/**
	 * @var bool
	 */
	protected $langEnabled;
	
    /**
     * @var bool
     */
    protected $allLangsEnabled;
	
    /**
     * @var SelectionAssociation[]
     */
    protected $subAssociations;
	
	/**
	 * @param string $fieldName
	 * @param boolean $langEnabled
	 * @param boolean $allLangsEnabled
	 * @param int $joinType
	 * @param boolean $retrievingEnabled
	 */
	public function __construct($fieldName, $langEnabled = true, $allLangsEnabled = false, $joinType = null, $retrievingEnabled = true) {
		$this->setFieldName($fieldName);
		$this->setLangEnabled($langEnabled);
		$this->setAllLangsEnabled($allLangsEnabled);
		$this->setJoinType($joinType);
		$this->setRetrievingEnabled($retrievingEnabled);
	}
	
	/**
	 * @return bool
	 */
	public function hasJoinType() {
		return !empty($this->joinType);
	}
	
	/**
	 * @return bool
	 */
	public function hasSubAssociations() {
		return !empty($this->subAssociations);
	}
	
	/**
	 * @param SelectionAssociation $subAssociation
	 */
	public function addSubAssociation(SelectionAssociation $subAssociation) {
		$this->subAssociations[$subAssociation->getFieldName()] = $subAssociation;
	}
	
	/**
	 * @param SelectionAssociation[] $subAssociations
	 */
	public function addSubAssociations($subAssociations) {
	    if (is_array($subAssociations)) {
	        foreach ($subAssociations as $subAssociation) {
	            $this->addSubAssociation($subAssociation);
	        }
	    }
	}
	
	/**
	 * @param string $fieldName
	 * @param int $joinType
	 * @param bool $retrievingEnabled
	 * @param bool $langEnabled
	 * @param bool $allLangsEnabled
	 * @return \muuska\dao\util\SelectionAssociation
	 */
	public function addSubAssociationFromParams($fieldName, $joinType = null, $retrievingEnabled = true, $langEnabled = true, $allLangsEnabled = false) {
		return $this->setSubAssociationParams($fieldName, $joinType, $retrievingEnabled, $langEnabled, $allLangsEnabled);
	}
	
	/**
	 * @param string $fieldName
	 * @return bool
	 */
	public function hasSubAssociation($fieldName) {
		return (isset($this->subAssociations[$fieldName]));
	}
	
	/**
	 * @param string $key
	 * @param bool $getNewIfNotExist
	 * @param bool $addToListIfNotExist
	 * @return SelectionAssociation
	 */
	public function getSubAssociationByKey($key, $getNewIfNotExist = false, $addToListIfNotExist = false) {
		$object = null;
		if($this->hasSubAssociation($key)){
			$object = $this->subAssociations[$key];
		}else{
			$object = $getNewIfNotExist ? App::daos()->createSelectionAssociation($key, null) : $object;
			if($addToListIfNotExist && ($object != null)){
				$this->addSubAssociation($object);
			}
		}
		return $object;
	}
	/**
	 * @param string $fieldName
	 * @param int $joinType
	 * @param bool $retrievingEnabled
	 * @param bool $langEnabled
	 * @param bool $allLangsEnabled
	 * @return \muuska\dao\util\SelectionAssociation
	 */
	public function setSubAssociationParams($fieldName, $joinType = null, $retrievingEnabled = true, $langEnabled = true, $allLangsEnabled = false) {
		$object = $this->getSubAssociationByKey($fieldName, true, true);
		$object->setFieldName($fieldName);
		$object->setLangEnabled($langEnabled);
		$object->setAllLangsEnabled($allLangsEnabled);
		$object->setJoinType($joinType);
		$object->setRetrievingEnabled($retrievingEnabled);
		return $object;
	}
	
    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @return number
     */
    public function getJoinType()
    {
        return $this->joinType;
    }

    /**
     * @return boolean
     */
    public function isRetrievingEnabled()
    {
        return $this->retrievingEnabled;
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
     * @return SelectionAssociation[]
     */
    public function getSubAssociations()
    {
        return $this->subAssociations;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @param int $joinType
     */
    public function setJoinType($joinType)
    {
        $this->joinType = $joinType;
    }

    /**
     * @param boolean $retrievingEnabled
     */
    public function setRetrievingEnabled($retrievingEnabled)
    {
        $this->retrievingEnabled = $retrievingEnabled;
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
     * @param SelectionAssociation[] $subAssociations
     */
    public function setSubAssociations($subAssociations)
    {
        $this->subAssociations = array();
        $this->addSubAssociations($subAssociations);
    }
}
