<?php
namespace muuska\util;

class AbstractExtraDataProvider implements ExtraDataProvider
{
	/**
	 * @var array
	 */
	private $extraData;
	
    /**
     * {@inheritDoc}
     * @see \muuska\util\ExtraDataProvider::getExtra()
     */
    public function getExtra($key, $defaultValue = null){
        return $this->hasExtra($key) ? $this->extraData[$key] : $defaultValue;
    }
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\util\ExtraDataProvider::hasExtra()
	 */
	public function hasExtra($key){
	    return isset($this->extraData[$key]);
    }
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\util\ExtraDataProvider::removeExtra()
	 */
	public function removeExtra($key){
	    unset($this->extraData[$key]);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\ExtraDataProvider::addExtra()
     */
    public function addExtra($key, $value){
        $this->setExtra($key, $value);
    }
    
	/**
	 * {@inheritDoc}
	 * @see \muuska\util\ExtraDataProvider::setExtra()
	 */
	public function setExtra($key, $value){
	    $this->extraData[$key] = $value;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\ExtraDataProvider::addExtraFromArray()
     */
    public function addExtraFromArray($array){
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $this->addExtra($key, $value);
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\ExtraDataProvider::getAllExtra()
     */
    public function getAllExtra()
    {
        return $this->extraData;
    }
    
    public function removeAllExtra()
    {
        $this->extraData = array();
    }
}
