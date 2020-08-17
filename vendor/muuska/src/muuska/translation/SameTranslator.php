<?php
namespace muuska\translation;

class SameTranslator implements Translator
{
    /**
     * {@inheritDoc}
     * @see \muuska\translation\Translator::translate()
     */
    public function translate($lang, $string, $context = null) {
        return $string;
    }
}