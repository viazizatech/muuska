<?php
namespace muuska\getter\option;

use muuska\getter\Getter;

class OptionLabelGetter implements Getter{
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data)
    {
        return $data->getLabel();
    }
}