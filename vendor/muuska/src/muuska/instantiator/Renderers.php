<?php
namespace muuska\instantiator;

class Renderers
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Renderers
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $relativeFile
	 * @param string $basePath
	 * @param \muuska\translation\TemplateTranslator $baseTranslator
	 * @param string $innerPath
	 * @param \muuska\translation\TemplateTranslator $innerTranslator
	 * @return \muuska\renderer\template\source\PHPTemplate
	 */
	public function createPHPTemplate($relativeFile, $basePath, \muuska\translation\TemplateTranslator $baseTranslator = null, $innerPath = null, \muuska\translation\TemplateTranslator $innerTranslator = null)
	{
	    return new \muuska\renderer\template\source\PHPTemplate($relativeFile, $basePath, $baseTranslator, $innerPath, $innerTranslator);
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\renderer\DefaultHtmlContentRenderer
	 */
	public function createDefaultHtmlContentRenderer($callback, $initialParams = null)
	{
	    return new \muuska\renderer\DefaultHtmlContentRenderer($callback, $initialParams);
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\renderer\value\DefaultValueRenderer
	 */
	public function createDefaultValueRenderer($callback, $initialParams = null)
	{
	    return new \muuska\renderer\value\DefaultValueRenderer($callback, $initialParams);
	}
	
	/**
	 * @param \muuska\option\provider\OptionProvider $optionProvider
	 * @param \muuska\getter\Getter $finalValueGetter
	 * @return \muuska\renderer\value\OptionLabelRenderer
	 */
	public function createOptionLabelRenderer(\muuska\option\provider\OptionProvider $optionProvider, \muuska\getter\Getter $finalValueGetter = null)
	{
	    return new \muuska\renderer\value\OptionLabelRenderer($optionProvider, $finalValueGetter);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param string $field
	 * @param \muuska\getter\Getter $finalModelGetter
	 * @return \muuska\renderer\value\ModelFileRenderer
	 */
	public function createModelFileRenderer(\muuska\model\ModelDefinition $modelDefinition, $field, \muuska\getter\Getter $finalModelGetter = null)
	{
	    return new \muuska\renderer\value\ModelFileRenderer($modelDefinition, $field, $finalModelGetter);
	}
	
	/**
	 * @param \muuska\getter\Getter $finalClassGetter
	 * @param boolean $prependFA
	 * @return \muuska\renderer\value\ClassIconValueRenderer
	 */
	public function createClassIconValueRenderer(\muuska\getter\Getter $finalClassGetter = null, $prependFA = true)
	{
	    return new \muuska\renderer\value\ClassIconValueRenderer($finalClassGetter, $prependFA);
	}
	
	/**
	 * @param string $altText
	 * @param \muuska\getter\Getter $finalSrcGetter
	 * @return \muuska\renderer\value\ImageValueRenderer
	 */
	public function createImageValueRenderer($altText = '', \muuska\getter\Getter $finalSrcGetter = null)
	{
	    return new \muuska\renderer\value\ImageValueRenderer($altText, $finalSrcGetter);
	}
	
	/**
	 * @param \muuska\getter\Getter $valueGetter
	 * @return \muuska\renderer\value\SimpleValueRenderer
	 */
	public function createSimpleValueRenderer(\muuska\getter\Getter $valueGetter)
	{
	    return new \muuska\renderer\value\SimpleValueRenderer($valueGetter);
	}
}
