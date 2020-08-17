<?php
namespace muuska\getter;

class JsonDecoder implements Getter
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
     * @var bool
     */
    protected $assoc;
    
    /**
     * @var int
     */
    protected $depth;
    
    /**
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     * @param Getter $finalGetter
     */
    public function __construct($assoc = null, $depth = null, $options = null, Getter $finalGetter = null){
        $this->assoc = $assoc;
        $this->finalGetter = $finalGetter;
        $this->options = $options;
        $this->depth = $depth;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data){
        return $this->decode($this->getFinalData($data));
    }
    
    /**
     * @param mixed $data
     * @return mixed
     */
    public function decode($data){
        return ($data !== null) ? json_decode($data, $this->assoc, $this->depth, $this->options) : null;
    }
    
    /**
     * @param mixed $data
     * @return mixed
     */
    public function getFinalData($data) {
        return (($this->finalGetter !== null) && ($data !== null)) ? $this->finalGetter->get($data) : $data;
    }
}
