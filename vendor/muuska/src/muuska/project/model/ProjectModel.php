<?php
namespace muuska\project\model;

use muuska\project\ProjectInfo;

class ProjectModel implements ProjectInfo{
	
    /**
     * @var int
     */
    protected $id;
	
	/**
	 * @var string
	 */
	protected $type;
	
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var string
	 */
	protected $mainClass;
	
	/**
	 * @var bool
	 */
	protected $active;
	
	/**
	 * @var bool
	 */
	protected $translationMoved;
	
	/**
	 * @var string
	 */
	protected $version;
	
	/**
	 * @var string
	 */
	protected $eventString;
	
	/**
	 * @var string
	 */
	protected $lastUpgradeDate;
	
	/**
	 * @var string
	 */
	protected $creationDate;
	
	/**
	 * @var string
	 */
	protected $lastModifiedDate;
	
	private $events;
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\project\ProjectInfo::getEvents()
	 */
	public function getEvents(){
	    if($this->events === null){
	        $this->events = array();
	        if(!empty($this->eventString)){
	            $this->events = explode(',', $this->eventString);
	        }
	    }
	    return $this->events;
	}
	
	/**
	 * @param array $events
	 */
	public function setEvents($events) {
	    $this->events = $events;
	    if(!empty($this->events)){
	        $this->eventString = implode(',', $this->events);
	    }
	}
	
	public function isRegisterAtEvent($code){
	    return in_array($code, $this->getEvents());
	}
    
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
    public function getType()
    {
        return $this->type;
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
    public function getMainClass()
    {
        return $this->mainClass;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getEventString()
    {
        return $this->eventString;
    }

    /**
     * @return string
     */
    public function getLastUpgradeDate()
    {
        return $this->lastUpgradeDate;
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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $mainClass
     */
    public function setMainClass($mainClass)
    {
        $this->mainClass = $mainClass;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @param string $eventString
     */
    public function setEventString($eventString)
    {
        $this->eventString = $eventString;
    }

    /**
     * @param string $lastUpgradeDate
     */
    public function setLastUpgradeDate($lastUpgradeDate)
    {
        $this->lastUpgradeDate = $lastUpgradeDate;
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
     * @return boolean
     */
    public function isTranslationMoved()
    {
        return $this->translationMoved;
    }

    /**
     * @param boolean $translationMoved
     */
    public function setTranslationMoved($translationMoved)
    {
        $this->translationMoved = $translationMoved;
    }
}