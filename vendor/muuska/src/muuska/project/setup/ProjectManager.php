<?php
namespace muuska\project\setup;


interface ProjectManager
{
    /**
     * @param ProjectSetup $projectSetup
     * @return bool
     */
    public function install(ProjectSetup $projectSetup);
    
    /**
     * @param \muuska\project\Project $project
     * @return bool
     */
    public function uninstall(\muuska\project\Project $project);
    
    /**
     * @param \muuska\project\Project $project
     * @return bool
     */
    public function activate(\muuska\project\Project $project);
    
    /**
     * @param \muuska\project\Project $project
     * @return bool
     */
    public function deactivate(\muuska\project\Project $project);
}