<?php
namespace muuska\instantiator;

class Translations
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Translations
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @return \muuska\translation\SameTranslator
	 */
	public function createSameTranslator() {
	    return new \muuska\translation\SameTranslator();
	}
	
	/**
	 * @param \muuska\translation\loader\TranslationLoader $translationLoader
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @return \muuska\translation\DefaultTranslator
	 */
	public function createDefaultTranslator(\muuska\translation\loader\TranslationLoader $translationLoader, \muuska\translation\Translator $alternativeTranslator = null) {
	    return new \muuska\translation\DefaultTranslator($translationLoader, $alternativeTranslator);
	}
	
	/**
	 * @param \muuska\translation\Translator $translator
	 * @param string $lang
	 * @return \muuska\translation\DefaultLangTranslator
	 */
	public function createDefaultLangTranslator(\muuska\translation\Translator $translator, $lang) {
	    return new \muuska\translation\DefaultLangTranslator($translator, $lang);
	}
	
	/**
	 * @param \muuska\translation\loader\TranslationLoader $translationLoader
	 * @param \muuska\translation\loader\MultipleLoader $multipleLoader
	 * @param string $keyPrefix
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @return \muuska\translation\DefaultTemplateTranslator
	 */
	public function createDefaultTemplateTranslator(\muuska\translation\loader\TranslationLoader $translationLoader, \muuska\translation\loader\MultipleLoader $multipleLoader = null, $keyPrefix = null, \muuska\translation\Translator $alternativeTranslator = null) {
	    return new \muuska\translation\DefaultTemplateTranslator($translationLoader, $multipleLoader, $keyPrefix, $alternativeTranslator);
	}
	
	/**
	 * @param string $filePattern
	 * @return \muuska\translation\loader\source\JsonTranslationLoader
	 */
	public function createJSONTranslationLoader($filePattern) {
	    return new \muuska\translation\loader\source\JSONTranslationLoader($filePattern);
	}
	
	/**
	 * @param string $filePattern
	 * @return \muuska\translation\loader\source\PHPFileTranslationLoader
	 */
	public function createPHPFileTranslationLoader($filePattern) {
	    return new \muuska\translation\loader\source\PHPFileTranslationLoader($filePattern);
	}
	
	/**
	 * @param array $array
	 * @return \muuska\translation\loader\source\ArrayTranslationLoader
	 */
	public function createArrayTranslationLoader($array) {
	    return new \muuska\translation\loader\source\ArrayTranslationLoader($array);
	}
	
	/**
	 * @param \muuska\translation\loader\TranslationLoader $mainLoader
	 * @return \muuska\translation\loader\DefaultMultipleLoader
	 */
	public function createDefaultMultipleLoader(\muuska\translation\loader\TranslationLoader $mainLoader) {
	    return new \muuska\translation\loader\DefaultMultipleLoader($mainLoader);
	}
	
	/**
	 * @param \muuska\translation\loader\TranslationLoader $mainLoader
	 * @param string $key
	 * @return \muuska\translation\loader\TreeTranslationLoader
	 */
	public function createTreeTranslationLoader(\muuska\translation\loader\TranslationLoader $mainLoader, $key) {
	    return new \muuska\translation\loader\TreeTranslationLoader($mainLoader, $key);
	}
	
	/**
	 * @param \muuska\translation\loader\MultipleLoader $multipleLoader
	 * @return \muuska\translation\loader\DefaultControllerTranslationLoader
	 */
	public function createDefaultControllerTranslationLoader(\muuska\translation\loader\MultipleLoader $multipleLoader) {
	    return new \muuska\translation\loader\DefaultControllerTranslationLoader($multipleLoader);
	}
	
	/**
	 * @param \muuska\translation\loader\ControllerTranslationLoader $controllerTranslationLoader
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @return \muuska\translation\DefaultControllerTranslator
	 */
	public function createDefaultControllerTranslator(\muuska\translation\loader\ControllerTranslationLoader $controllerTranslationLoader, \muuska\translation\Translator $alternativeTranslator = null) {
	    return new \muuska\translation\DefaultControllerTranslator($controllerTranslationLoader, $alternativeTranslator);
	}
	
	/**
	 * @param string $baseDir
	 * @param boolean $themePrefixEnabled
	 * @param string $themeName
	 * @param string $loaderSource
	 * @return \muuska\translation\TranslationStore
	 */
	public function createTranslationStore($baseDir, $themePrefixEnabled = false, $themeName = null, $loaderSource = null) {
	    return new \muuska\translation\TranslationStore($baseDir, $themePrefixEnabled, $themeName, $loaderSource);
	}
	
	
	/**
	 * @param string $type
	 * @param string $name
	 * @return \muuska\translation\config\DefaultTranslationConfig
	 */
	public function createDefaultTranslationConfig($type, $name) {
	    return new \muuska\translation\config\DefaultTranslationConfig($type, $name);
	}
	
	/**
	 * @param string $name
	 * @param boolean $relatedToTheme
	 * @return \muuska\translation\config\ControllerTranslationConfig
	 */
	public function createControllerTranslationConfig($name, $relatedToTheme = true) {
	    return new \muuska\translation\config\ControllerTranslationConfig($name, $relatedToTheme);
	}
	
	/**
	 * @param string $path
	 * @param boolean $relatedToTheme
	 * @return \muuska\translation\config\TemplateTranslationConfig
	 */
	public function createTemplateTranslationConfig($path, $relatedToTheme = true) {
	    return new \muuska\translation\config\TemplateTranslationConfig($path, $relatedToTheme);
	}
	
	/**
	 * @param string $path
	 * @param boolean $relatedToTheme
	 * @return \muuska\translation\config\JSTranslationConfig
	 */
	public function createJSTranslationConfig($path, $relatedToTheme = true) {
	    return new \muuska\translation\config\JSTranslationConfig($path, $relatedToTheme);
	}
	
	/**
	 * @param string $name
	 * @return \muuska\translation\config\CustomTranslationConfig
	 */
	public function createCustomTranslationConfig($name) {
	    return new \muuska\translation\config\CustomTranslationConfig($name);
	}
	
	/**
	 * @param string $name
	 * @return \muuska\translation\config\HelperTranslationConfig
	 */
	public function createHelperTranslationConfig($name) {
	    return new \muuska\translation\config\HelperTranslationConfig($name);
	}
	
	/**
	 * @param string $name
	 * @return \muuska\translation\config\OptionTranslationConfig
	 */
	public function createOptionTranslationConfig($name) {
	    return new \muuska\translation\config\OptionTranslationConfig($name);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @return \muuska\translation\config\ModelTranslationConfig
	 */
	public function createModelTranslationConfig(\muuska\model\ModelDefinition $modelDefinition) {
	    return new \muuska\translation\config\ModelTranslationConfig($modelDefinition);
	}
	
	/**
	 * @return \muuska\translation\config\MainTranslationConfig
	 */
	public function createMainTranslationConfig() {
	    return new \muuska\translation\config\MainTranslationConfig();
	}
	
	/**
	 * @return \muuska\translation\config\ValidationTranslationConfig
	 */
	public function createValidationTranslationConfig() {
	    return new \muuska\translation\config\ValidationTranslationConfig();
	}
	
	/**
	 * @param string $alertType
	 * @return \muuska\translation\config\AlertTranslationConfig
	 */
	public function createAlertTranslationConfig($alertType) {
	    return new \muuska\translation\config\AlertTranslationConfig($alertType);
	}
}
