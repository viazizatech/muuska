<?php
namespace muuska\getter;

class SameGetter implements Getter{
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data)
    {
        return $data;
    }
}