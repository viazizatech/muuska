<?php
namespace muuska\localization\model;

use muuska\localization\LanguageInfo;
use muuska\model\AbstractModel;
use muuska\util\App;

class LanguageModel extends AbstractModel implements LanguageInfo{
	
    /**
     * @var int
     */
    protected $id;
	
	/**
	 * @var string
	 */
	protected $uniqueCode;
	
	/**
	 * @var string
	 */
	protected $language;
	
	/**
	 * @var string
	 */
	protected $country;
	
	/**
	 * @var string
	 */
	protected $variant;
	
	/**
	 * @var bool
	 */
	protected $active;
	
	/**
	 * @var string
	 */
	protected $ISO3Language;
	
	/**
	 * @var string
	 */
	protected $ISO3Country;
	
	/**
	 * @var string
	 */
	protected $displayName;
	
	/**
	 * @var string
	 */
	protected $displayLanguage;
	
	/**
	 * @var string
	 */
	protected $displayCountry;
	
	/**
	 * @var string
	 */
	protected $displayVariant;
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\localization\LanguageInfo::getDisplayCountry()
	 */
	public function getDisplayCountry($lang = null){
	    return $this->getTranslatableValue('displayCountry', $lang);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\localization\LanguageInfo::getDisplayLanguage()
	 */
	public function getDisplayLanguage($lang = null){
	    return $this->getTranslatableValue('displayLanguage', $lang);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\localization\LanguageInfo::getDisplayName()
	 */
	public function getDisplayName($lang = null){
	    return $this->getTranslatableValue('displayName', $lang);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\localization\LanguageInfo::getDisplayVariant()
	 */
	public function getDisplayVariant($lang = null){
	    return $this->getTranslatableValue('displayVariant', $lang);
	}
	
	/**
	 * @param string $field
	 * @param string $lang
	 * @return string
	 */
	protected function getTranslatableValue($field, $lang = null){
	    if(empty($lang)){
	        $lang = App::getApp()->getDefaultLang();
	    }
	    return $this->getPropertyValueByLang($field, $lang);
	}
	
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUniqueCode()
    {
        return $this->uniqueCode;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getISO3Language()
    {
        return $this->ISO3Language;
    }

    /**
     * @return string
     */
    public function getISO3Country()
    {
        return $this->ISO3Country;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $uniqueCode
     */
    public function setUniqueCode($uniqueCode)
    {
        $this->uniqueCode = $uniqueCode;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @param string $variant
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @param string $ISO3Language
     */
    public function setISO3Language($ISO3Language)
    {
        $this->ISO3Language = $ISO3Language;
    }

    /**
     * @param string $ISO3Country
     */
    public function setISO3Country($ISO3Country)
    {
        $this->ISO3Country = $ISO3Country;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\localization\LanguageInfo::getFullCode()
     */
    public function getFullCode()
    {
        return $this->getLanguage() . '-' .$this->getCountry();
    }
    
    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @param string $displayLanguage
     */
    public function setDisplayLanguage($displayLanguage)
    {
        $this->displayLanguage = $displayLanguage;
    }

    /**
     * @param string $displayCountry
     */
    public function setDisplayCountry($displayCountry)
    {
        $this->displayCountry = $displayCountry;
    }

    /**
     * @param string $displayVariant
     */
    public function setDisplayVariant($displayVariant)
    {
        $this->displayVariant = $displayVariant;
    }
}