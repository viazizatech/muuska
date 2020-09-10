<?php
namespace muuska\html\input;

use muuska\asset\constants\AssetNames;

class RichTextEditor extends AbstractHtmlInput{
	/**
	 * @var string
	 */
	protected $componentName = 'rich_text_editor';
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct($name, $value = null) {
	    parent::__construct($name, $value);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::prepare()
	 */
	public function prepare(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    parent::prepare($globalConfig, $callerConfig);
	    $this->setJsInitializationRequired('richEditor', true);
	    if ($globalConfig->hasAssetSetter() && $globalConfig->hasTheme()) {
	        $globalConfig->getTheme()->createAssetGroup(AssetNames::RICH_TEXT_EDITOR_GROUP, $globalConfig->getAssetSetter());
	    }
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if(!empty($this->name)){
	        $attributes['name'] = $this->name;
	    }
	    return $attributes;
	}
}