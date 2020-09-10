<?php
namespace muuska\project\setup;

interface ProjectUpgrade
{
    /**
     * @return bool
     */
    public function upgrade();
    
    /**
     * @return string[]
     */
    public function getEvents();

    /**
     * @return string
     */
    public function getToken();

    /**
     * @return boolean
     */
    public function isEventChanged();
}