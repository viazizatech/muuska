<?php
namespace muuska\getter;

use muuska\util\FunctionCallback;

class DefaultGetter extends FunctionCallback implements Getter{
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data)
    {
        $result = null;
        if ($this->callback !== null) {
            if(empty($this->initialParams)){
                $result = call_user_func($this->callback, $data);
            }else{
                $result = call_user_func($this->callback, $this->initialParams, $data);
            }
        }
        return $result;
    }
}