<?php
namespace muuska\html\listing;
class FullyClickableList extends AbstractList{
    /**
     * @var string
     */
    protected $componentName = 'fully_clickable_list';
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\AbstractList::renderStaticItem()
     */
    public function renderStaticItem(\muuska\html\listing\item\ListItem $item, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
        return $item->renderFullyClickablePresentation($globalConfig, $callerConfig);
    }
}