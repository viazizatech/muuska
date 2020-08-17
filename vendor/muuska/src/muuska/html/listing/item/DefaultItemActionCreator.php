<?php
namespace muuska\html\listing\item;
use muuska\util\FunctionCallback;

class DefaultItemActionCreator extends FunctionCallback implements ItemActionCreator{
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
     * @see \muuska\html\listing\item\ItemActionCreator::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\item\ItemActionCreator::createAction()
     */
    public function createAction($itemData, $item, $urlParams = array(), $anchor = '', $mode = null)
    {
        $result = null;
        if($this->callback !== null){
            if(empty($this->initialParams)){
                $result = call_user_func($this->callback, $itemData, $item, $anchor, $mode);
            }else{
                $result = call_user_func($this->callback, $this->initialParams, $itemData, $item, $anchor, $mode);
            }
        }
        return $result;
    }
}