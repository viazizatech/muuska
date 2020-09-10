<?php
namespace muuska\translation;

use muuska\util\App;

class DefaultLangTranslator implements LangTranslator
{
    /**
     * @var Translator
     */
    protected $translator;
    
    /**
     * @var string
     */
    protected $lang;
    
    /**
     * @param Translator $translator
     * @param string $lang
     */
    public function __construct(Translator $translator, $lang){
        $this->translator = $translator;
        $this->lang = $lang;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\LangTranslator::l()
     */
    public function l($string, $context = null){
        return $this->translate($this->lang, $string, $context);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\Translator::translate()
     */
    public function translate($lang, $string, $context = null) {
        return $this->translator->translate($lang, $string, $context);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\LangTranslator::getNew()
     */
    public function getNew($lang){
        return App::translations()->createDefaultLangTranslator($this->translator, $lang);
    }
}