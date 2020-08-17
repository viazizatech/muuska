<?php
namespace muuska\url\event;

use muuska\project\constants\ProjectType;
use muuska\util\App;
use muuska\util\event\EventObject;

class UrlCreationEvent extends EventObject
{
    /**
     * @var \muuska\url\UrlCreationInput
     */
    protected $input;
    
    /**
     * @var string
     */
    protected $url;
    
    /**
     * @var \muuska\project\Project
     */
    private $project;
    
    /**
     * @param \muuska\project\Application $source
     * @param \muuska\url\UrlCreationInput $input
     * @param array $params
     */
    public function __construct(\muuska\project\Application $source, \muuska\url\UrlCreationInput $input, $params = array()){
        parent::__construct($source, $params);
        $this->input = $input;
    }
    
    /**
     * @return string
     */
    public function getFinalEventCode()
    {
        return strtolower($this->input->getSubAppName()).'_url_creation';
    }
    
    /**
     * @return \muuska\project\SubProject
     */
    public function getSubProject()
    {
        $result = null;
        $project = $this->getProject();
        if ($project !== null) {
            $result = $project->getSubProject($this->input->getSubAppName());
        }
        return $result;
    }
    
    /**
     * @return \muuska\project\Project
     */
    public function getProject()
    {
        if($this->project === null){
            $projectType = $this->input->getProjectType();
            $projectName = $this->input->getProjectName();
            if(empty($projectType)){
                $projectType = ProjectType::APPLICATION;
                $projectName = '';
            }
            $this->project = App::getApp()->getProject($projectType, $projectName);
        }
        return $this->project;
    }
    
    /**
     * @return \muuska\url\UrlCreationInput
     */
    public function getInput()
    {
        return $this->input;
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $this->preventDefault();
        $this->stopPropagation();
    }
}