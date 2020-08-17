<?php
namespace muuska\html\listing;

class Accordion extends AbstractList{
    /**
     * @var string
     */
    protected $componentName = 'acccordion';
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\AbstractList::renderStaticItem()
     */
    public function renderStaticItem(\muuska\html\listing\item\ListItem $item, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
        return $item->renderAllPresentationFields($globalConfig, $callerConfig);
    }
}