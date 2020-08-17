<?php
namespace muuska\dao\event;

interface AppDAOEventListener
{
    /**
     * @param string $code
     * @param \muuska\dao\event\DAOEvent $event
     */
    public function onAppDAOEvent($code, \muuska\dao\event\DAOEvent $event);
}
