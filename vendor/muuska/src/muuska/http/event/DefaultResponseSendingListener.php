<?php
namespace muuska\http\event;

use muuska\util\FunctionCallback;

class DefaultResponseSendingListener extends FunctionCallback implements ResponseSendingListener
{
    /**
     * {@inheritDoc}
     * @see \muuska\http\event\ResponseSendingListener::beforeSend()
     */
    public function beforeSend(ResponseSendingEvent $event){
        if($this->callback !== null){
            if(empty($this->initialParams)){
                call_user_func($this->callback, $event);
            }else{
                call_user_func($this->callback, $this->initialParams, $event);
            }
        }
    }
}
