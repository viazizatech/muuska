<?php
namespace muuska\project;

class ProjectInput
{
    /**
     * @var string
     */
    protected $corePath;
    
    /**
     * @var \muuska\dao\DAOFactory
     */
    protected $daoFactory;
    
    /**
     * @param string $corePath
     * @param \muuska\dao\DAOFactory $daoFactory
     */
    public function __construct($corePath, \muuska\dao\DAOFactory $daoFactory) {
        $this->corePath = $corePath;
        $this->daoFactory = $daoFactory;
    }
    
    /**
     * @return string
     */
    public function getCorePath()
    {
        return $this->corePath;
    }

    /**
     * @return \muuska\dao\DAOFactory
     */
    public function getDaoFactory()
    {
        return $this->daoFactory;
    }
}
