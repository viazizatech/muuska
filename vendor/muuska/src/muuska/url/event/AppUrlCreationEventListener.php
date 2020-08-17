<?php
namespace muuska\url\event;

interface AppUrlCreationEventListener
{
    /**
     * @param \muuska\url\event\UrlCreationEvent $event
     */
    public function onAppUrlCreation(\muuska\url\event\UrlCreationEvent $event);
}