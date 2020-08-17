<?php
namespace muuska\security\model;

use muuska\model\AbstractModel;

class AuthentificationAccessModel extends AbstractModel
{
    /**
     * @var int
     */
    protected $authentificationId;
    
    /**
     * @var int
     */
    protected $resourceId;
    
    /**
     * @return int
     */
    public function getAuthentificationId()
    {
        return $this->authentificationId;
    }

    /**
     * @return int
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param int $authentificationId
     */
    public function setAuthentificationId($authentificationId)
    {
        $this->authentificationId = $authentificationId;
    }

    /**
     * @param int $resourceId
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }  
}