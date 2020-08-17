<?php
namespace muuska\project\setup;

use muuska\util\App;

class DefaultProjectManager implements ProjectManager
{
    /**
     * @var \muuska\dao\DAOFactory
     */
    protected $daoFactory;
    
    /**
     * @param \muuska\dao\DAOFactory $daoFactory
     */
    public function __construct(\muuska\dao\DAOFactory $daoFactory){
        $this->daoFactory = $daoFactory;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectManager::install()
     */
    public function install(ProjectSetup $projectSetup){
        $project = $projectSetup->getProject();
        $model = App::projects()->createProjectModel();
        $model->setActive(true);
        $model->setVersion($project->getVersion());
        $model->setName($project->getName());
        $model->setType($project->getType());
        $model->setMainClass(get_class($project));
        $model->setEvents($projectSetup->getEvents());
        $model->setTranslationMoved(false);
        return $this->daoFactory->getDAO(App::projects()->getProjectModelDefinition(), 'json')->add($model);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectManager::uninstall()
     */
    public function uninstall(\muuska\project\Project $project){
        return $this->daoFactory->getDAO(App::projects()->getProjectModelDefinition(), 'json')->delete($project->getInstalledInfo());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectManager::activate()
     */
    public function activate(\muuska\project\Project $project){
        return $this->daoFactory->getDAO(App::projects()->getProjectModelDefinition(), 'json')->activate($project->getInstalledInfo());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\setup\ProjectManager::deactivate()
     */
    public function deactivate(\muuska\project\Project $project){
        return $this->daoFactory->getDAO(App::projects()->getProjectModelDefinition(), 'json')->deactivate($project->getInstalledInfo());
    }
}