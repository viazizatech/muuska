<?php
namespace muuska\html\listing\item;
use muuska\util\FunctionCallback;

class DefaultListItemCreator extends FunctionCallback implements ListItemCreator{
    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\item\ListItemCreator::createItem()
     */
    public function createItem($data, \muuska\html\listing\item\ListItemContainer $listItemContainer, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null)
    {
        $result = null;
        if($this->callback !== null){
            if(empty($this->initialParams)){
                $result = call_user_func($this->callback, $data, $listItemContainer, $globalConfig, $callerConfig);
            }else{
                $result = call_user_func($this->callback, $this->initialParams, $data, $listItemContainer, $globalConfig, $callerConfig);
            }
        }
        return $result;
	}
}