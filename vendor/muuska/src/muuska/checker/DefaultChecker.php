<?php
namespace muuska\checker;
use muuska\util\FunctionCallback;

class DefaultChecker extends FunctionCallback implements Checker{
    /**
     * {@inheritDoc}
     * @see \muuska\checker\Checker::check()
     */
    public function check($data)
    {
        $result = null;
        if($this->callback !== null){
            if(empty($this->initialParams)){
                $result = call_user_func($this->callback, $data);
            }else{
                $result = call_user_func($this->callback, $this->initialParams, $data);
            }
        }
        return $result;
    }
}
