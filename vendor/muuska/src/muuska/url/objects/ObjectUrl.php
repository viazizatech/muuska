<?php
namespace muuska\url\objects;
interface ObjectUrl{
    /**
     * @param mixed $data
     * @param array $params
     * @param string $anchor
     * @param int $mode
     */
    public function createUrl($data, $params = array(), $anchor = '', $mode = null);
}