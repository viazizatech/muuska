<?php
namespace muuska\translation;

use muuska\util\App;

class DefaultTemplateTranslator extends DefaultTranslator implements TemplateTranslator
{
    /**
     * @var \muuska\translation\loader\MultipleLoader
     */
    protected $multipleLoader;
    
    /**
     * @var string
     */
    protected $keyPrefix;
    
    /**
     * @param \muuska\translation\loader\TranslationLoader $translationLoader
     * @param \muuska\translation\loader\MultipleLoader $multipleLoader
     * @param string $keyPrefix
     * @param \muuska\translation\Translator $alternativeTranslator
     */
    public function __construct(\muuska\translation\loader\TranslationLoader $translationLoader, \muuska\translation\loader\MultipleLoader $multipleLoader = null, $keyPrefix = null, \muuska\translation\Translator $alternativeTranslator = null){
        parent::__construct($translationLoader, $alternativeTranslator);
        $this->multipleLoader = $multipleLoader;
        $this->keyPrefix = $keyPrefix;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\TemplateTranslator::getNewTranslator()
     */
    public function getNewTranslator($relativeFile)
    {
        $result = $this;
        if($this->multipleLoader !== null){
            $result = App::translations()->createDefaultTemplateTranslator($this->multipleLoader->getLoader($this->keyPrefix.$relativeFile), $this->multipleLoader, $this->keyPrefix, $this->alternativeTranslator);
        }
        return $result;
    }
}