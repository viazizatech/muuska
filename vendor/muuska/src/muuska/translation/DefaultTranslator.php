<?php
namespace muuska\translation;

class DefaultTranslator implements Translator
{
    /**
     * @var \muuska\translation\Translator
     */
    protected $alternativeTranslator;
    
    /**
     * @var \muuska\translation\loader\TranslationLoader
     */
    protected $translationLoader;
    
    /**
     * @param \muuska\translation\loader\TranslationLoader $translationLoader
     * @param \muuska\translation\Translator $alternativeTranslator
     */
    public function __construct(\muuska\translation\loader\TranslationLoader $translationLoader, \muuska\translation\Translator $alternativeTranslator = null){
        $this->translationLoader = $translationLoader;
        $this->alternativeTranslator = $alternativeTranslator;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\Translator::translate()
     */
    public function translate($lang, $string, $context = null) {
        $result = $string;
        $translations = ($this->translationLoader !== null) ? $this->translationLoader->getTranslations($lang) : array();
        if(isset($translations[$string])){
            $data = $translations[$string];
            if(is_array($data)){
                $context = (string)$context;
                if(isset($data[$context])){
                    $result = $data[$context];
                }elseif(isset($data[''])){
                    $result = $data[''];
                }
            }else{
                $result = $data;
            }
        }elseif($this->alternativeTranslator !== null){
            $result = $this->alternativeTranslator->translate($lang, $string, $context);
        }
        return $result;
    }
}