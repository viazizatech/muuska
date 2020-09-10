<?php
namespace muuska\option\provider;

use muuska\util\App;

abstract class AbstractOptionProvider implements OptionProvider, TranslatableOptionProvider
{
    /**
    * @var string
    */
    protected $lang;
    
    /**
     * @var array
     */
    private $tmpData = array();
    
    /**
     * @param static $lang
     */
    public function __construct($lang = null){
        $this->setLang($lang);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\TranslatableOptionProvider::getLangOptionProvider()
     */
    public function getLangOptionProvider($lang){
        return new static($lang);
    }
    
    /**
     * @param string $string
     * @param string $context
     * @return string
     */
    public function l($string, $context = '') {
        $translator = $this->getTranslator();
        return ($translator !== null) ? $translator->l($string, $context) : $string;
    }
        
    /**
     * @return \muuska\translation\LangTranslator
     */
    public function getTranslator(){
        if(!array_key_exists('translator', $this->tmpData)){
            $lang = empty($this->lang) ? App::getApp()->getDefaultLang() : $this->lang;
            $translator = $this->createTranslator();
            if($translator !== null){
                $this->tmpData['translator'] = App::translations()->createDefaultLangTranslator($translator, $lang);
            }else{
                $this->tmpData['translator'] = null;
            }
        }
        return $this->tmpData['translator'];
    }
    
    /**
     * @return \muuska\translation\Translator
     */
    protected function createTranslator(){}
    
    /**
     * @param string $name
     * @return \muuska\translation\Translator
     */
    protected function getFrameworkTranslator($name){
        return App::getFrameworkTranslator(App::translations()->createOptionTranslationConfig($name));
    }
    
    /**
     * @param \muuska\project\Project $project
     * @param string $name
     * @return \muuska\translation\Translator
     */
    protected function getProjectTranslator(\muuska\project\Project $project, $name){
        return $project->getTranslator(App::translations()->createOptionTranslationConfig($name));
    }
    
    /**
     * @param string $name
     * @return \muuska\translation\Translator
     */
    protected function getAppTranslator($name){
        return App::getAppTranslator(App::translations()->createOptionTranslationConfig($name));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\OptionProvider::getOptions()
     */
    public function getOptions(){
        if(!isset($this->tmpData['options'])){
            $this->initOptions();
        }
        return $this->tmpData['options'];
    }
    
    protected abstract function initOptions();
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\OptionProvider::getLabelFromValue()
     */
    public function getLabelFromValue($value){
        $option = $this->getOptionFromValue($value);
        return ($option !== null) ? $option->getLabel() : '';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\OptionProvider::getOptionFromValue()
     */
    public function getOptionFromValue($value){
        $result = null;
        $options = $this->getOptions();
        if(isset($options[$value])){
            $result = $options[$value];
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\OptionProvider::contains()
     */
    public function contains($value){
        $options = $this->getOptions();
        return isset($options[$value]);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\OptionProvider::getAllValues()
     */
    public function getAllValues(){
        return App::getArrayTools()->getArrayValues($this->getOptions(), App::getters()->createOptionValueGetter());
    }
    
    /**
     * @param array $array
     */
    protected function addOptionsFromAssociativeArray($array){
        if (is_array($array)) {
            foreach ($array as $option) {
                $this->addOption(App::options()->createAssociativeArrayOption($option));
            }
        }
    }
    
    /**
     * @param array $array
     */
    protected function addOptionsFromKeyValueArray($array){
        if (is_array($array)) {
            foreach ($array as $value => $label) {
                $this->addArrayOption($value, $label);
            }
        }
    }
    
    /**
     * @param mixed $value
     * @param string $label
     * @param array $additionalParams
     * @return \muuska\option\AssociativeArrayOption
     */
    protected function createArrayOption($value, $label, $additionalParams = array()) {
        $option = array('value' => $value, 'label' => $label);
        if(!empty($additionalParams)){
            $option = array_merge($option, $additionalParams);
        }
        return App::options()->createAssociativeArrayOption($option);
    }
    
    /**
     * @param mixed $value
     * @param string $label
     * @param array $additionalParams
     */
    protected function addArrayOption($value, $label, $additionalParams = array()) {
        $this->addOption($this->createArrayOption($value, $label, $additionalParams));
    }
    
    /**
     * @param \muuska\option\Option $option
     */
    protected function addOption(\muuska\option\Option $option) {
        $this->tmpData['options'][$option->getValue()] = $option;
    }
    
    /**
     * @param \muuska\option\Option[] $options
     */
    protected function addOptions($options) {
        if (is_array($options)) {
            foreach ($options as $option) {
                $this->addOption($option);
            }
        }
    }
    
    /**
     * @param \muuska\option\Option[] $options
     */
    protected function setOptions($options) {
        $this->resetOptions();
        $this->addOptions($options);
    }
    
    protected function resetOptions() {
        $this->tmpData['options'] = array();
    }
    
    
    
    /**
     * @param string $lang
     */
    protected function setLang($lang){
        $this->lang = $lang;
    }
}

