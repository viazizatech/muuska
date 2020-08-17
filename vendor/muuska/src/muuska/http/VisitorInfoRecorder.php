<?php
namespace muuska\http;

interface VisitorInfoRecorder
{
    /**
     * @param string $name
     * @return bool
     */
    public function hasValue($name);
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function addValue($name, $value);
	
	/**
	 * Binds an object to this session, using the name specified.
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	public function setValue($name, $value);
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getValue($name, $defaultValue = null);
	
	/**
	 * @param string $prefix
	 * @return array
	 */
	public function getValuesByPrefix($prefix);
	
	/**
	 * @return array
	 */
	public function getAllValues();
	
	/**
	 * Removes the object bound with the specified name from this session.
	 * 
	 * @param string $name
	 */
	public function removeValue($name);
	
	/**
	 * @param string $prefix
	 */
	public function removeValuesByPrefix($prefix);
	
	public function removeAllValues();
	
	/**
	 * @param string $name
	 * @param array $array
	 */
	public function addArrayValue($name, $array);
	
	/**
	 * @param array $array
	 */
	public function addValuesFromArray($array);
	
	/**
	 * @param string $name
	 * @return array
	 */
	public function getArrayValues($name);
}