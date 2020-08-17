<?php
namespace muuska\security\model;

use muuska\model\AbstractModel;

class GroupAccessModel extends AbstractModel
{
    /**
     * @var int
     */
    protected $groupId;
    
    /**
     * @var int
     */
    protected $resourceId;

    /**
     * @return int
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @return int
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param int $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * @param int $resourceId
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }    
}