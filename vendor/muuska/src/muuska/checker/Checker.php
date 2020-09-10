<?php
namespace muuska\checker;
interface Checker{
    /**
     * @param mixed $data
     * @return bool
     */
    public function check($data);
}