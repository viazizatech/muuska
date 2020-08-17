<?php
namespace muuska\url\event;

interface UrlCreationEventListener
{
    /**
     * @param \muuska\url\event\UrlCreationEvent $event
     */
    public function onUrlCreation(\muuska\url\event\UrlCreationEvent $event);
}