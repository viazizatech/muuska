<?php
namespace muuska\html\listing\item;
interface ItemActionCreator{
    /**
     * @param mixed $data
     * @param \muuska\html\listing\item\ListItem $item
     * @param array $urlParams
     * @param string $anchor
     * @param int $mode
     * @return \muuska\html\HtmlContent
     */
    public function createAction($itemData, $item, $urlParams = array(), $anchor = '', $mode = null);
    
    /**
     * @return string
     */
    public function getName();
}