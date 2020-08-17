<?php
namespace muuska\security\model;

use muuska\model\AbstractModel;

class AuthentificationGroupModel extends AbstractModel
{
    /**
     * @var int
     */
    protected $authentificationId;
    
    /**
     * @var int
     */
    protected $groupId;

    /**
     * @return int
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param int $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }
    
    /**
     * @return int
     */
    public function getAuthentificationId()
    {
        return $this->authentificationId;
    }

    /**
     * @param int $authentificationId
     */
    public function setAuthentificationId($authentificationId)
    {
        $this->authentificationId = $authentificationId;
    }
}