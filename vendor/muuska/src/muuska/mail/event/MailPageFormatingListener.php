<?php
namespace muuska\mail\event;

interface MailPageFormatingListener
{
    /**
     * @param MailPageFormatingEvent $event
     */
    public function onMailPageFormating($code, MailPageFormatingEvent $event);
}
