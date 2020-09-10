<?php
namespace muuska\localization;

interface LanguageInfo{
    
	/**
	 * @return int
	 */
	public function getId();
	
	/**
	 * @return bool
	 */
	public function isActive();
	
	/**
	 * @return string
	 */
	public function getUniqueCode();
	
	/**
	 * Returns the language code of this Locale.
	 * 
	 * @return string
	 */
	public function getLanguage();
	
	/**
	 * Returns the country/region code for this locale, which should either be the empty string, an uppercase ISO 3166 2-letter code, or a UN M.49 3-digit code.
	 *
	 * @return string
	 */
	public function getCountry();
	
	/**
	 * Returns the variant code for this locale.
	 *
	 * @return string
	 */
	public function getVariant();
	
	/**
	 * Returns a three-letter abbreviation of this locale's language.
	 *
	 * @return string
	 */
	public function getISO3Language();
	
	/**
	 * Returns a three-letter abbreviation for this locale's country.
	 *
	 * @return string
	 */
	public function getISO3Country();
	
	/**
	 * Returns the language code of this Locale.
	 *
	 * @return string
	 */
	public function getFullCode();
	
	/**
	 * Returns a name for the locale's country that is appropriate for display to the user.
	 *
	 * @param string $lang
	 * @return string
	 */
	public function getDisplayCountry($lang = null);
	
	/**
	 * Returns a name for the locale's language that is appropriate for display to the user.
	 *
	 * @param string $lang
	 * @return string
	 */
	public function getDisplayLanguage($lang = null);
	
	/**
	 * Returns a name for the locale that is appropriate for display to the user.
	 *
	 * @param string $lang
	 * @return string
	 */
	public function getDisplayName($lang = null);
	
	/**
	 * Returns a name for the locale's variant code that is appropriate for display to the user.
	 *
	 * @param string $lang
	 * @return string
	 */
	public function getDisplayVariant($lang = null);
}