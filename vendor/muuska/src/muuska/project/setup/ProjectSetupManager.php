<?php
namespace muuska\project\setup;


interface ProjectSetupManager
{
    /**
     * @param ProjectSetup $setup
     */
    public function addSetup(ProjectSetup $setup);
    
    /**
     * @param ProjectManager $projectManager
     */
    public function setProjectManager(ProjectManager $projectManager);
}