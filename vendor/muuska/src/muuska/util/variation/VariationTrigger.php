<?php
namespace muuska\util\variation;

interface VariationTrigger
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getValue();
}
