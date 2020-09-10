<?php
namespace muuska\dao;
use muuska\util\App;

class DAOFactory{
    private static $daoClasses = array();
    private static $sources = array();
    private static $applicationInitialized;
    protected static $mainSource;
	
	/**
	 * @param string $mainSource
	 * @throws \Exception
	 */
	public final function __construct($mainSource)
    {
        if(self::$applicationInitialized){
            throw new \Exception('Factory is already initialized');
        }
        self::$applicationInitialized = true;
        self::$mainSource = $mainSource;
    }
    
    /**
     * @param \muuska\dao\DAOSource $source
     */
    public function registerSource(\muuska\dao\DAOSource $source)
    {
        $sourceName = $source->getName();
        if(!isset(self::$sources[$sourceName])){
            self::$sources[$sourceName] = $source;
        }
    }
    
    /**
     * @param string $sourceName
     * @return bool
     */
    public function isSourceRegistered($sourceName)
    {
        return isset(self::$sources[$sourceName]);
    }
    
    /**
     * @param \muuska\project\Project $project
     * @param string $customDAOSource
     * @return \muuska\dao\DAOSource
     */
    public function getSourceInstance(\muuska\project\Project $project, $customDAOSource = null)
    {
        $source = null;
        $sourceKey = $customDAOSource;
        if(empty($sourceKey)){
            if($project->hasSpecificDAOSource()){
                $sourceKey = $project->getSpecificDAOSource($this);
            }else{
                $sourceKey = self::$mainSource;
            }
        }
        if(!empty($sourceKey) && isset(self::$sources[$sourceKey])){
            $source = self::$sources[$sourceKey];
        }else{
            throw new \Exception('Source not found');
        }
        return $source;
    }
	
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $customDAOSource
     * @return \muuska\dao\DAO
     */
    public function getDAO(\muuska\model\ModelDefinition $modelDefinition, $customDAOSource = null){
        $key = $customDAOSource .'_'. $modelDefinition->getFullName();
        if (!isset(self::$daoClasses[$key]) || (self::$daoClasses[$key] === null)){
            if(empty($customDAOSource) && $modelDefinition->hasSpecificDAOSource()){
                $customDAOSource = $modelDefinition->getSpecificDAOSource();
            }
            $project = $modelDefinition->getProject();
            $daoSource = $this->getSourceInstance($project, $customDAOSource);
            self::$daoClasses[$key] = App::getApp()->createAppModelDAO($project, $modelDefinition, $this, $daoSource);
        }
        return self::$daoClasses[$key];
    }
}
