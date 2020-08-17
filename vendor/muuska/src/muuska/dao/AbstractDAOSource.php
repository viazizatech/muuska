<?php
namespace muuska\dao;
use muuska\util\App;

abstract class AbstractDAOSource implements DAOSource{
	/**
     * @var string
     */
    protected $name;
    
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAOSource::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAOSource::protectString($string, $htmlOk)
     */
    public function protectString($string, $htmlOk = false)
    {
        if (App::getApp()->getMainConfiguration()->getBool('magis_quotes_gpc', true)) {
            $string = stripslashes($string);
        }
        
        if (!is_numeric($string)) {
            $string = $this->protectStringImplementation($string);
            
            if (!$htmlOk) {
                $string = strip_tags(App::getStringTools()->nl2br($string));
            }
        }
        
        return $string;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAOSource::installProject($input)
     */
    public function installProject(ProjectDAOInstallInput $input){
        $result = true;
        if(!$input->getProject()->isInstalled()){
            $result = $this->installProjectImplementation($input);
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAOSource::uninstallProject($input)
     */
    public function uninstallProject(ProjectDAOUninstallInput $input){
        $result = true;
        if($input->getProject()->isInstalled()){
            $result = $this->uninstallProjectImplementation($input);
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAOSource::upgradeProject($input)
     */
    public function upgradeProject(ProjectDAOUpgradeInput $input){
        $result = true;
        if($input->getProject()->isInstalled() && !$input->getProject()->isUpToDate()){
            $result = $this->upgradeProjectImplementation($input);
        }
        return $result;
    }
    
    /**
     * @param string $str
     * @return string
     */
    protected function protectStringImplementation($str){
        return $str;
    }
    
    /**
     * @param ProjectDAOInstallInput $input
     * @return bool
     */
    protected function installProjectImplementation(ProjectDAOInstallInput $input){
        return true;
    }
    
    /**
     * @param ProjectDAOUninstallInput $input
     * @return bool
     */
    protected function uninstallProjectImplementation(ProjectDAOUninstallInput $input){
        return true;
    }
    
    /**
     * @param ProjectDAOUpgradeInput $input
     * @return bool
     */
    protected function upgradeProjectImplementation(ProjectDAOUpgradeInput $input){
        return true;
    }
}
