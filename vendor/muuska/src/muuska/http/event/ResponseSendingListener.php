<?php
namespace muuska\http\event;

interface ResponseSendingListener
{
    /**
     * @param ResponseSendingEvent $event
     */
    public function beforeSend(ResponseSendingEvent $event);
}
