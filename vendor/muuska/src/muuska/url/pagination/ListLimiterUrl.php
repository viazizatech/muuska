<?php
namespace muuska\url\pagination;

interface ListLimiterUrl
{
    /**
     * @param mixed $value
     * @return string
     */
    public function createListLimiterUrl($value);
    
    /**
     * @return string
     */
    public function getEditableListLimiterUrlPattern();
}