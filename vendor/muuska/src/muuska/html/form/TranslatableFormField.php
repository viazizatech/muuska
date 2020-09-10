<?php
namespace muuska\html\form;

use muuska\html\ChildrenContainer;

class TranslatableFormField extends ChildrenContainer{
    /**
     * @var string
     */
    protected $componentName = 'translatable_field';
    
    /**
     * @var string
     */
    protected $activeLang;
    
    /**
     * @var string
     */
    protected $helpText;
    
    /**
     * @var bool
     */
    protected $required = false;
    
    /**
     * @var string
     */
    protected $error;
    
    private static $langLangSwitcherContents = array();
    
    /**
     * @param string $name
     * @param string $label
     * @param string $activeLang
     */
    public function __construct($name, $label, $activeLang) {
        $this->setLabel($label);
        $this->setName($name);
        $this->setActiveLang($activeLang);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlElement::getOtherClasses()
     */
    protected function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        $result = parent::getOtherClasses($globalConfig, $callerConfig);
        if($this->hasError()){
            $result[] = 'has_error';
        }
        if($this->isRequired()){
            $result[] = 'required';
        }
        return $result;
    }
    
    /**
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function drawError($prefix = '', $suffix = '') {
        return $this->drawString($this->error, $prefix, $suffix);
    }
    
    /**
     * @return bool
     */
    public function hasError(){
        return !empty($this->error);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param \muuska\html\HtmlContent $renderer
     * @return string
     */
    public function renderLangSwitcher(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $renderer = null) {
        $content = '';
        $useCache = true;
        if($useCache && isset(self::$langLangSwitcherContents[$this->activeLang])){
            $content = self::$langLangSwitcherContents[$this->activeLang];
        }else{
            $langList = $this->htmls()->createHtmlElement();
            $renderer = ($renderer === null) ? $langList->createThemeTemplate($globalConfig, 'others/lang_switcher') : $renderer;
            $langList->setRenderer($renderer);
            $langList->addExtra('activeLang', $this->activeLang);
            $langList->addExtra('activeLangLabel', $this->activeLang);
            $langList->addExtra('languages', $this->children);
            $content = $langList->generate($globalConfig, $callerConfig);
            if($useCache){
                self::$langLangSwitcherContents[$this->activeLang] = $content;
            }
        }
        return $content;
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function renderHelpText(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '') {
        return $this->renderString($this->helpText, $globalConfig, $callerConfig, 'helpText', $prefix, $suffix);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function renderLabel(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '') {
        return $this->renderString($this->label, $globalConfig, $callerConfig, 'label', $prefix, $suffix);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $stringClasses
     * @param string[] $excludedClasses
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function renderLangFields(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $stringClasses = null, $excludedClasses = null, $prefix = '', $suffix = '') {
        $result = '';
        foreach ($this->children as $child) {
            $currentCallerConfig = $this->createCallerConfig($this->concatTwoStrings('translatable_field', $stringClasses), null, array('data-lang' => $child->getName()), null, null, $excludedClasses);
            $currentCallerConfig->addDisabledArea('label');
            if(!$this->isActiveLangField($child)){
                $currentCallerConfig->setVisible(false);
            }
            $result .= $child->generate($globalConfig, $currentCallerConfig);
        }
        return $this->drawString($result, $prefix, $suffix);
    }
    
    /**
     * @return boolean
     */
    public function hasHelpText() {
        return !empty($this->helpText);
    }
    
    /**
     * @param \muuska\html\HtmlContent $child
     * @return boolean
     */
    public function isActiveLangField(\muuska\html\HtmlContent $child) {
        return ($this->activeLang === $child->getName());
    }
    
    /**
     * @return string
     */
    public function getActiveLang()
    {
        return $this->activeLang;
    }

    /**
     * @return string
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * @param string $activeLang
     */
    public function setActiveLang($activeLang)
    {
        $this->activeLang = $activeLang;
    }

    /**
     * @param string $helpText
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;
    }
    
    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param boolean $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }
    
    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }
}