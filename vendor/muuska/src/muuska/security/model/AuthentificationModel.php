<?php
namespace muuska\security\model;

use muuska\security\AuthentificationInfo;
use muuska\model\AbstractModel;

class AuthentificationModel extends AbstractModel implements AuthentificationInfo
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
    protected $login;
    
    /**
     * @var string
     */
    protected $password;
    
    /**
     * @var bool
     */
    protected $superUser;
    
    /**
     * @var string
     */
    protected $preferredLang;
    
    /**
     * @var string
     */
    protected $creationDate;
    
    /**
     * @var string
     */
    protected $lastModifiedDate;
    
    /**
     * @var bool
     */
    protected $deleted;
    
    /**
     * @var bool
     */
    protected $active;
    
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
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return boolean
     */
    public function isSuperUser()
    {
        return $this->superUser;
    }

    /**
     * @return string
     */
    public function getPreferredLang()
    {
        return $this->preferredLang;
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
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param int $id
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
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param boolean $superUser
     */
    public function setSuperUser($superUser)
    {
        $this->superUser = $superUser;
    }

    /**
     * @param string $preferredLang
     */
    public function setPreferredLang($preferredLang)
    {
        $this->preferredLang = $preferredLang;
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
     * @param boolean $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\AuthentificationInfo::getName()
     */
    public function getName()
    {
        return $this->getLogin();
    }
    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
}