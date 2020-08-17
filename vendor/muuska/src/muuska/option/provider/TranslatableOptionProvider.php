<?php
namespace muuska\option\provider;

interface TranslatableOptionProvider extends OptionProvider
{
    /**
     * @param string $lang
     * @return OptionProvider
     */
    public function getLangOptionProvider($lang);
}

