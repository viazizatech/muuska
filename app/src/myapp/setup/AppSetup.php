<?php
namespace myapp\setup;

use muuska\project\setup\AbstractProjectSetup;

class AppSetup extends AbstractProjectSetup
{
    public function __construct(\muuska\project\Application $application){
        $this->project = $application;
    }
}
