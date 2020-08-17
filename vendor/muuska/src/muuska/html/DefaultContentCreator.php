<?php
namespace muuska\html;
use muuska\util\FunctionCallback;

class DefaultContentCreator extends FunctionCallback implements ContentCreator{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @param string $name
     * @param callable $callback
     * @param array $initialParams
     */
    public function __construct($name, $callback, $initialParams = null) {
        parent::__construct($callback, $initialParams);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\ContentCreator::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\ContentCreator::createContent()
     */
    public function createContent()
    {
        $result = null;
        if($this->callback !== null){
            if(empty($this->initialParams)){
                $result = call_user_func($this->callback);
            }else{
                $result = call_user_func($this->callback, $this->initialParams);
            }
        }
        return $result;
    }
}