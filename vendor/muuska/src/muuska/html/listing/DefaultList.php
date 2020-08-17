<?php
namespace muuska\html\listing;
class DefaultList extends AbstractList{
    /**
     * @var string
     */
    protected $componentName = 'default_list';
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\AbstractList::renderStaticItem()
     */
    public function renderStaticItem(\muuska\html\listing\item\ListItem $item, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
        return $item->getFullString($globalConfig, $callerConfig, $item->renderAllFields($globalConfig, $callerConfig, '<div class="fields">', '</div>').$item->renderAllActions($globalConfig, $callerConfig, '<div class="actions">', '</div>'));
    }
}