<?php
namespace mymod;

use muuska\project\setup\AbstractProjectSetup;

class MyModSetup extends AbstractProjectSetup
{
    public function __construct(\muuska\project\ProjectInput $projectInput){
        $this->project = new MyMod($projectInput);
    }
    
    public function getDisplayName($lang){
        return $this->translate($lang, 'My mod', 'project_display_name');
    }
    
    public function getDescription($lang){
        return $this->translate($lang, 'My mod description', 'project_description');
    }
}
