<?php
namespace muuska\getter;

interface Getter
{
    /**
     * @param mixed $data
     * @return mixed
     */
    public function get($data);
}
