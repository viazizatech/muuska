<?php
namespace muuska\dao\util;

use muuska\model\ModelCollection;

class DAOListResult extends ModelCollection
{
    /**
     * @var int
     */
    protected $totalWithoutLimit;

    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param array $data
     * @param int $totalWithoutLimit
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, array $data, $totalWithoutLimit = null) {
        parent::__construct($modelDefinition, $data);
        $this->setTotalWithoutLimit($totalWithoutLimit);
    }
    
	/**
	 * @return int
	 */
	public function getTotalWithoutLimit() {
        return $this->totalWithoutLimit;
    }
    /**
     * @param int $totalWithoutLimit
     */
    public function setTotalWithoutLimit($totalWithoutLimit) {
        $this->totalWithoutLimit = $totalWithoutLimit;
    }
}
