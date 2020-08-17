<?php
namespace muuska\util;

interface ExtraDataProvider
{
	/**
	 * @param string $key
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getExtra($key, $defaultValue = null);
	
	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasExtra($key);
	
	/**
	 * @param string $key
	 */
	public function removeExtra($key);
	
	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setExtra($key, $value);
	
	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function addExtra($key, $value);
	
	/**
	 * @param array $array
	 */
	public function addExtraFromArray($array);
	
	/**
	 * @return array
	 */
	public function getAllExtra();
	
	public function removeAllExtra();
}
