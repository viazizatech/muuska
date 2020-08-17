<?php
namespace muuska\dao\util;

class SortOption extends FieldParameter
{
    /**
     * @var int
     */
    protected $direction;
	
	/**
	 * @param string $fieldName
	 * @param int $direction
	 */
	public function __construct($fieldName, $direction = null) {
		$this->setFieldName($fieldName);
		$this->setDirection($direction);
	}
	
	/**
	 * @return bool
	 */
	public function hasDirection(){
	    return !empty($this->direction);
	}
	
	/**
	 * @return int
	 */
	public function getDirection() {
		return $this->direction;
	}
	
	/**
	 * @param int $direction
	 */
	public function setDirection($direction){
		$this->direction=$direction;
	}
}
