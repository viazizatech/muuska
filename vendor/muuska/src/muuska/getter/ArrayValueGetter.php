<?php
namespace muuska\getter;

class ArrayValueGetter implements Getter
{
    /**
     * @var string
     */
    protected $key;
    
    /**
     * @var Getter
     */
    protected $finalGetter;
    
    /**
     * @param string $key
     * @param Getter $finalGetter
     */
    public function __construct($key, Getter $finalGetter = null){
        $this->$key = $key;
        $this->finalGetter = $finalGetter;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data)
    {
        $finalData = $this->getFinalData($data);
        return isset($finalData[$this->key]) ? $finalData[$this->key] : null;
    }
    
    /**
     * @param mixed $data
     * @return mixed
     */
    public function getFinalData($data) {
        return (($this->finalGetter !== null) && ($data !== null)) ? $this->finalGetter->get($data) : $data;
    }
}

