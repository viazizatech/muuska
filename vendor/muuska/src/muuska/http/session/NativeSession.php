<?php
namespace muuska\http\session;

class NativeSession implements Session
{
    /**
     * @var bool
     */
    protected $new = false;
    
    /**
     * @var string
     */
    protected $id;
    
    /**
     * @param string $id
     */
    public function __construct($id = null) {
        session_start();
        if(empty($id)){
            $this->new = true;
            $this->id = session_id();
        }else{
            $this->id = $id;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\session\Session::getCreationTime()
     */
    public function getCreationTime(){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\session\Session::getId()
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\session\Session::getLastAccessedTime()
     */
    public function getLastAccessedTime(){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\session\Session::getMaxInactiveInterval()
     */
    public function getMaxInactiveInterval(){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\session\Session::invalidate()
     */
    public function invalidate(){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\session\Session::isNew()
     */
    public function isNew(){
        return $this->new;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\session\Session::setMaxInactiveInterval()
     */
    public function setMaxInactiveInterval($maxInactiveInterval){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\session\Session::destroy()
     */
    public function destroy(){
        return session_destroy();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::hasValue()
     */
    public function hasValue($name){
        return isset($_SESSION[$name]);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::addValue()
     */
    public function addValue($name, $value){
        $this->setValue($name, $value);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::setValue()
     */
    public function setValue($name, $value){
        $_SESSION[$name] = $value;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::getValue()
     */
    public function getValue($name, $defaultValue = null){
        return $this->hasValue($name) ? $_SESSION[$name] : $defaultValue;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::getValuesByPrefix()
     */
    public function getValuesByPrefix($prefix){
        $values = $this->getAllValues();
        $result = array();
        foreach ($values as $key => $value) {
            if(strpos($key, $prefix) === 0){
                $result[$key] = $value;
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::getAllValues()
     */
    public function getAllValues(){
        return $_SESSION;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::removeValue()
     */
    public function removeValue($name){
        if ($this->hasValue($name)) {
            unset($_SESSION[$name]);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::removeValuesByPrefix()
     */
    public function removeValuesByPrefix($prefix){
        $names = array_keys($this->getValuesByPrefix($prefix));
        foreach ($names as $name) {
            $this->removeValue($name);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::removeAllValues()
     */
    public function removeAllValues(){
        session_unset();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::addArrayValue()
     */
    public function addArrayValue($name, $array){
        $this->addValue($name, $array);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::addValuesFromArray()
     */
    public function addValuesFromArray($array){
        if(is_array($array)){
            foreach ($array as $key => $value) {
                $this->addValue($key, $value);
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\VisitorInfoRecorder::getArrayValues()
     */
    public function getArrayValues($name){
        $value = $this->getValue($name, array());
        return is_array($value) ? $value : array();
    }
}
