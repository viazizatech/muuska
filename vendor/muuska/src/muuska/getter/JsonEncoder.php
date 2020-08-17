<?php
namespace muuska\getter;

class JsonEncoder implements Getter
{
    /**
     * @var Getter
     */
    protected $finalGetter;
    
    /**
     * @var int
     */
    protected $options;
    
    /**
     * @var int
     */
    protected $depth;
    
    /**
     * @param int $options
     * @param int $depth
     * @param Getter $finalGetter
     */
    public function __construct($options = null, $depth = null, Getter $finalGetter = null){
        $this->finalGetter = $finalGetter;
        $this->options = $options;
        $this->depth = $depth;
    }
    
    /**
     * @param mixed $data
     * @return mixed
     */
    public function get($data){
        return $this->encode($this->getFinalData($data));
    }
    
    /**
     * @param mixed $data
     * @return mixed
     */
    public function getFinalData($data) {
        return (($this->finalGetter !== null) && ($data !== null)) ? $this->finalGetter->get($data) : $data;
    }
    
    /**
     * @param mixed $data
     */
    public function encode($data){
        return ($data !== null) ? json_encode($data, $this->options, $this->depth) : '';
    }
}
