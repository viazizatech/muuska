<?php
namespace muuska\project;

interface ProjectInfo{
	/**
	 * @return int
	 */
	public function getId();
	
	/**
	 * @return string
	 */
	public function getType();
	
	/**
	 * @return string
	 */
	public function getName();
	
	/**
	 * @return bool
	 */
	public function isActive();
	
	/**
	 * @return string
	 */
	public function getVersion();
	
	/**
	 * @return array
	 */
	public function getEvents();

	/**
	 * @param string $code
	 * @return string
	 */
	public function isRegisterAtEvent($code);
	
	/**
	 * @return string
	 */
	public function getLastUpgradeDate();
	
	/**
	 * @return string
	 */
	public function getCreationDate();
	
	/**
	 * @return string
	 */
	public function getLastModifiedDate();

    /**
     * @return string
     */
    public function getMainClass();
    
    /**
     * @return bool
     */
    public function isTranslationMoved();
}