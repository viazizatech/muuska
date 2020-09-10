<?php
namespace muuska\util\event;

interface AppEventListener
{
    /**
     * @param \muuska\util\event\AppInitializationEvent $event
     */
    public function onAppInitialization(\muuska\util\event\AppInitializationEvent $event);
}
