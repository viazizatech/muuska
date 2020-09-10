<?php
namespace muuska\getter;

class ToStringGetter implements Getter
{
    /**
     * @var Getter
     */
    protected $finalGetter;
    
    /**
     * @param Getter $finalGetter
     */
    public function __construct(Getter $finalGetter = null){
        $this->finalGetter = $finalGetter;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data)
    {
        $finalData = $this->getFinalData($data);
        return ($finalData !== null) ? $finalData->__toString() : '';
    }
    
    /**
     * @param mixed $data
     * @return mixed
     */
    public function getFinalData($data) {
        return (($this->finalGetter !== null) && ($data !== null)) ? $this->finalGetter->get($data) : $data;
    }
}
