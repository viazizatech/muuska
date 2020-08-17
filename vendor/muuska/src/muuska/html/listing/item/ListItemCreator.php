<?php
namespace muuska\html\listing\item;
interface ListItemCreator{
	/**
	 * @param mixed $data
	 * @param \muuska\html\listing\item\ListItemContainer $listItemContainer
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return \muuska\html\listing\item\ListItem
	 */
    public function createItem($data, \muuska\html\listing\item\ListItemContainer $listItemContainer, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null);
}