<?php
namespace muuska\dao\event;

interface DAOEventListener
{
    /**
     * @param string $code
     * @param \muuska\dao\event\DAOEvent $event
     */
    public function onDAOEvent($code, \muuska\dao\event\DAOEvent $event);
}
