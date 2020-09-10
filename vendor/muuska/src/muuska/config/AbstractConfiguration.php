<?php
namespace muuska\config;

use muuska\util\App;

abstract class AbstractConfiguration implements Configuration{
    /**
     * @var array
     */
    protected $configValues;
    
    /**
     * @var bool
     */
    protected $autoSave = false;
    
    protected abstract function load();
    
    protected function autoLoadConfigValues(){
        if($this->configValues === null){
            $this->load();
        }
        return $this->configValues;
    }
    
    protected function onChange(){
        if($this->autoSave){
            $this->save();
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getAllLangValues()
     */
    public function getAllLangValues($key)
    {
        return $this->getArray($key);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getAll()
     */
    public function getAll($prefix = null)
    {
        $result = array();
        $this->autoLoadConfigValues();
        if(!empty($prefix)){
            foreach ($this->configValues as $key => $value) {
                if(strpos($key, $prefix) === 0){
                    $result[$key] = $value;
                }
            }
        }else{
            $result = $this->configValues;
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::containsKey()
     */
    public function containsKey($key)
    {
        $this->autoLoadConfigValues();
        return isset($this->configValues[$key]);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::clear()
     */
    public function clear()
    {
        $this->configValues = array();
        $this->onChange();
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::isEmpty()
     */
    public function isEmpty()
    {
        return ($this->size() == 0);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getDouble()
     */
    public function getDouble($key, $defaultValue = 0)
    {
        return doubleval($this->get($key, $defaultValue));
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getArray()
     */
    public function getArray($key)
    {
        $result = array();
        $value = $this->get($key);
        if(is_array($value)){
            $result = $value;
        }elseif(!empty($value)){
            $value = $this->unserializeArray($value);
            if(is_array($value)){
                $result = $value;
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getString()
     */
    public function getString($key, $defaultValue = '')
    {
        return (string)$this->get($key, $defaultValue);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getFloat()
     */
    public function getFloat($key, $defaultValue = 0)
    {
        return (float)$this->get($key, $defaultValue);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getKeys()
     */
    public function getKeys($prefix = null)
    {
        $result = array();
        $this->autoLoadConfigValues();
        $keys = array_keys($this->configValues);
        if(!empty($prefix)){
            foreach ($keys as $key) {
                if(strpos($key, $prefix) === 0){
                    $result[] = $key;
                }
            }
        }else{
            $result = $keys;
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::setLangValues()
     */
    public function setLangValues($key, $values)
    {
        $this->setArray($key, $values);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::clearProperty()
     */
    public function clearProperty($key)
    {
        $this->autoLoadConfigValues();
        unset($this->configValues[$key]);
        $this->onChange();
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::setArray()
     */
    public function setArray($key, $values)
    {
        $this->setProperty($key, $this->serializeArray($values));
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getInt()
     */
    public function getInt($key, $defaultValue = 0)
    {
        return (int)$this->get($key, $defaultValue);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::size()
     */
    public function size()
    {
        $this->autoLoadConfigValues();
        return count($this->configValues);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::setProperty()
     */
    public function setProperty($key, $value)
    {
        $this->autoLoadConfigValues();
        $this->configValues[$key] = $value;
        $this->onChange();
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::get()
     */
    public function get($key, $defaultValue = null)
    {
        $this->autoLoadConfigValues();
        return $this->containsKey($key) ? $this->configValues[$key] : $defaultValue;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getTranslatableValue()
     */
    public function getTranslatableValue($key, $lang, $defaultValue = null)
    {
        $values = $this->getAllLangValues($key);
        return isset($values[$lang]) ? $values[$lang] : $defaultValue;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::setAutoSave()
     */
    public function setAutoSave($autoSave)
    {
        $this->autoSave = $autoSave;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getBool()
     */
    public function getBool($key, $defaultValue = false)
    {
        $result = $defaultValue;
        $value = $this->get($key, $defaultValue);
        if(empty($value) || ($value === '0') || ($value === 'false')){
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::getInnerConfiguration()
     */
    public function getInnerConfiguration($key){
        $this->autoLoadConfigValues();
        return App::configs()->createSubConfiguration($this, $key, $this->getArray($key));
    }
    
    /**
     * @param array $values
     * @return string
     */
    protected function serializeArray($values)
    {
        return serialize($values);
    }
    
    /**
     * @param string $content
     * @return array
     */
    protected function unserializeArray($content)
    {
        return unserialize($content);
    }
}