<?php
namespace muuska\security\model;

use muuska\model\AbstractModel;

class ResourceModel extends AbstractModel
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $code;
    
    /**
     * @var string
     */
    protected $label;
    
    /**
     * @var int
     */
    protected $accessRule;
    
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getAccessRule()
    {
        return $this->accessRule;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @param int $accessRule
     */
    public function setAccessRule($accessRule)
    {
        $this->accessRule = $accessRule;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
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
}