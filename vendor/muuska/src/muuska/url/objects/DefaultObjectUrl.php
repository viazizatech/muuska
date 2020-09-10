<?php
namespace muuska\url\objects;
use muuska\util\FunctionCallback;

class DefaultObjectUrl extends FunctionCallback implements ObjectUrl{
    /**
     * {@inheritDoc}
     * @see \muuska\url\objects\ObjectUrl::createUrl()
     */
    public function createUrl($data, $params = array(), $anchor = '', $mode = null) {
        $result = null;
        if($this->callback != null){
            if(empty($this->initialParams)){
                $result = call_user_func($this->callback, $data, $params, $anchor, $mode);
            }else{
                $result = call_user_func($this->callback, $this->initialParams, $data, $params, $anchor, $mode);
            }
        }
        return $result;
    }
}