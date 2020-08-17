<?php
namespace muuska\config;

interface Configuration{
    /**
     * @param bool $autoSave
     */
    public function setAutoSave($autoSave);
    
    /**
     * @param string $key
     */
    public function clearProperty($key);
    
    /**
     * @return bool
     */
    public function save();
    
    /**
     * @param string $key
     * @param mixed $value
     */
    public function setProperty($key, $value);
    
    /**
     * @param string $key
     * @param array $values
     */
    public function setArray($key, $values);
    
  /**
   * @param string $key
   * @param array $values
   */
    public function setLangValues($key, $values);
    
    public function clear();
    
    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get($key, $defaultValue = null);
    
    /**
     * @param string $key
     * @param string $lang
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getTranslatableValue($key, $lang, $defaultValue = null);
    
    /**
     * @param string $key
     * @return array
     */
    public function getAllLangValues($key);
    
    /**
     * @param string $key
     * @param string $defaultValue
     * @return string
     */
    public function getString($key, $defaultValue = '');
    
    /**
     * @param string $key
     * @param int $defaultValue
     * @return int
     */
    public function getInt($key, $defaultValue = 0);
    
    /**
     * @param string $key
     * @param bool $defaultValue
     * @return bool
     */
    public function getBool($key, $defaultValue = false);
    
    /**
     * @param string $key
     * @param float $defaultValue
     * @return float
     */
    public function getFloat($key, $defaultValue = 0);
    
    /**
     * @param string $key
     * @param double $defaultValue
     * @return double
     */
    public function getDouble($key, $defaultValue = 0);
    
    /**
     * @param string $key
     * @return array
     */
    public function getArray($key);
    
    /**
     * @param string $key
     * @return bool
     */
    public function containsKey($key);
    
    /**
     * @return bool
     */
    public function isEmpty();
    
    /**
     * @param string $prefix
     * @return array
     */
    public function getKeys($prefix = null);
    
    /**
     * @param string $prefix
     * @return array
     */
    public function getAll($prefix = null);
    
    /**
     * @return int
     */
    public function size();
    
    /**
     * @param string $key
     * @return Configuration
     */
    public function getInnerConfiguration($key);
}