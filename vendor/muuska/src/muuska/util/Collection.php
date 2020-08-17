<?php
namespace muuska\util;

class Collection implements \Countable, \Iterator, \ArrayAccess
{
	/**
	 * @var array
	 */
	protected $data;
	
	/**
	 * @var int
	 */
	protected $iterator = 0;
	
	/**
	 * @var int
	 */
	protected $total = 0;

	/**
	 * @param array $data
	 */
	public function __construct(array $data = array()) {
	    $this->setData($data);
    }
    
    /**
     * @return array
     */
    public function toArray() {
        return $this->data;
    }
    
    
    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return ($this->count() === 0);
    }
    
	/**
	 * @return mixed
	 */
	public function getFirst()
    {
		$first = null;
		if(isset($this->data[0])){
			$first = $this->data[0];
		}
        return $first;
    }
    
    /**
     * @param mixed $object
     */
    public function add($object)
    {
        $this->data[] = $object;
    }
    
    /**
     * @param Collection $collection
     */
    public function addCollection(Collection $collection)
    {
        $this->addArray($collection);
    }
    
    /**
     * @param array $array
     */
    public function addArray($array)
    {
        foreach ($array as $value) {
            $this->add($value);
        }
    }
	
    /**
     * @param mixed $data
     */
    protected function setData($data) {
        $this->data = $data;
    }
	
	/**
	 * {@inheritDoc}
	 * @see \Iterator::rewind()
	 */
	public function rewind()
    {
        $this->iterator = 0;
        $this->total = count($this->data);
    }
    
    /**
     * {@inheritDoc}
     * @see \Iterator::current()
     */
    public function current()
    {
        return isset($this->data[$this->iterator]) ? $this->data[$this->iterator] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \Iterator::valid()
     */
    public function valid()
    {
        return $this->iterator < $this->total;
    }
    
	/**
	 * {@inheritDoc}
	 * @see \Iterator::key()
	 */
	public function key()
    {
        return $this->iterator;
    }
    
    /**
     * {@inheritDoc}
     * @see \Iterator::next()
     */
    public function next()
    {
        $this->iterator++;
    }
	
    /**
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count() {
        return count($this->data);
    }
    
    /**
     * {@inheritDoc}
     * @see \ArrayAccess::offsetExists()
     */
    public function offsetExists($offset) {
		return isset($this->data[$offset]);
    }
    
    /**
     * {@inheritDoc}
     * @see \ArrayAccess::offsetGet()
     */
    public function offsetGet($offset) {
		return $this->data[$offset];
    }
    
    /**
     * {@inheritDoc}
     * @see \ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }
    
    /**
     * {@inheritDoc}
     * @see \ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }
}
