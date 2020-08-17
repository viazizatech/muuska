<?php
namespace muuska\html\listing\item;
interface ListItemContainer{
	/**
	 * @return mixed
	 */
	public function getData();
	
	/**
	 * @return \muuska\html\listing\item\ListItemCreator
	 */
	public function getItemCreator();
	
	/**
	 * @param \muuska\html\listing\item\ListItemCreator $itemCreator
	 */
	public function setItemCreator(?\muuska\html\listing\item\ListItemCreator $itemCreator);
	
	/**
	 * @return bool
	 */
	public function hasItemCreator();
	
	/**
	 * @param mixed $data
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return \muuska\html\listing\item\ListItem
	 */
	public function createItem($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null);
	
	/**
	 * @param mixed $data
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return \muuska\html\listing\item\ListItem
	 */
	public function defaultCreateItem($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null);
	
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 */
	public function drawItems(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null);
}