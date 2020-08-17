<?php
namespace muuska\security\model;

use muuska\model\AbstractModel;
use muuska\security\GroupInfo;

class GroupModel extends AbstractModel implements GroupInfo
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $subAppName;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var int
     */
    protected $parentId;
    
    /**
     * @var string
     */
    protected $creationDate;
    
    /**
     * @var string
     */
    protected $lastModifiedDate;
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSubAppName()
    {
        return $this->subAppName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return string
     */
    public function getLastModifiedDate()
    {
        return $this->lastModifiedDate;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $subAppName
     */
    public function setSubAppName($subAppName)
    {
        $this->subAppName = $subAppName;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @param string $lastModifiedDate
     */
    public function setLastModifiedDate($lastModifiedDate)
    {
        $this->lastModifiedDate = $lastModifiedDate;
    }
    
    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }
}