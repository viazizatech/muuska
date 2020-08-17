<?php
namespace mycompany\newapp\setup;

use muuska\project\setup\AbstractProjectSetup;

class NewAppSetup extends AbstractProjectSetup
{
    public function __construct(\muuska\project\Application $application){
        $this->project = $application;
    }
}
