<?php
namespace muuska\html\config\caller;
class ListItemCallerConfig extends DefaultHtmlCallerConfig{
    /**
     * @var \muuska\html\listing\AbstractList
     */
    protected $list;
    
    /**
     * @param \muuska\html\listing\AbstractList $list
     * @param \muuska\html\HtmlContent $callerInstance
     * @param string $stringClasses
     * @param array $styleAttributes
     * @param array $attributes
     * @param string[] $excludedAttributes
     * @param string[] $excludedStyleAttributes
     * @param string[] $excludedClasses
     */
    public function __construct(\muuska\html\listing\AbstractList $list, \muuska\html\HtmlContent $callerInstance, $stringClasses = null, $styleAttributes = null, $attributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null){
        parent::__construct($callerInstance, $stringClasses, $styleAttributes, $attributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
        $this->setList($list);
    }
        
    /**
     * @return \muuska\html\listing\AbstractList
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param \muuska\html\listing\AbstractList $listBody
     */
    public function setList($list)
    {
        $this->list = $list;
    }
}