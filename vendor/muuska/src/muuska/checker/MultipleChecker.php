<?php
namespace muuska\checker;

use muuska\constants\operator\LogicalOperator;

class MultipleChecker implements Checker{
    /**
     * @var Checker[]
     */
    protected $checkers;
    
    /**
     * @var int
     */
    protected $logicalOperator;
    
    /**
     * @param Checker[] $checkers
     * @param int $logicalOperator
     */
    public function __construct($checkers = array(), $logicalOperator = null){
        $this->setCheckers($checkers);
        $this->logicalOperator = $logicalOperator;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\checker\Checker::check()
     */
    public function check($data)
    {
        $result = true;
        foreach ($this->checkers as $checker) {
            $result = $checker->check($data);
            if($this->logicalOperator == LogicalOperator::OR_){
                if($result){
                    break;
                }
            }elseif(!$result){
                break;
            }
        }
        return $result;
    }
    
    /**
     * @param Checker[] $checkers
     */
    public function setCheckers($checkers) {
        $this->checkers = array();
    }
    
   /**
    * @param Checker[] $checkers
    */
    public function addCheckers($checkers) {
        if (is_array($checkers)) {
            foreach ($checkers as $checker) {
                $this->addChecker($checker);
            }
        }
    }
    
    /**
     * @param Checker $checker
     */
    public function addChecker(Checker $checker) {
        $this->checkers[] = $checker;
    }
}