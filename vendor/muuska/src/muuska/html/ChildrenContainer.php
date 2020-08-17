<?php
namespace muuska\html;

class ChildrenContainer extends HtmlCustomElement{
    /**
     * @var string
     */
    protected $componentName = 'children_container';
    
    /**
	 * @var string
	 */
	protected $label;
    
    /**
     * @var \muuska\html\HtmlContent[]
     */
    protected $children = array();
    
    /**
     * @param string $label
     */
    public function __construct($label = '') {
        $this->setLabel($label);
    }
    
    /**
     * @param \muuska\html\HtmlContent $child
     * @param string $key
     */
    public function addChild(\muuska\html\HtmlContent $child, $key = null){
        $key = empty($key) ? $child->getName() : $key;
        if(!empty($key)){
            $this->children[$key] = $child;
        }else{
            $this->children[] = $child;
        }
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasChild($name){
        return isset($this->children[$name]);
    }
    
    /**
     * @param string $name
     * @return \muuska\html\HtmlContent
     */
    public function getChild($name){
        return $this->hasChild($name) ? $this->children[$name] : null;
    }
    
    /**
     * @param string $name
     */
    public function removeChild($name){
        if ($this->hasChild($name)) {
            unset($this->children[$name]);
        }
    }
    
    /**
     * @param \muuska\html\HtmlContent[] $contents
     */
    public function addChildren($contents){
        if (is_array($contents)) {
            foreach ($contents as $content) {
                $this->addChild($content);
            }
        }
    }
    
    /**
     * @param \muuska\html\HtmlContent[] $contents
     */
    public function setChildren($contents){
        $this->children = array();
        $this->addChildren($contents);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $prefix
     * @param string $suffix
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function generateChildren(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
        return $this->renderContentList($this->children, $globalConfig, $callerConfig, 'children', $prefix, $suffix, $currentCallerConfig);
    }
    
    /**
     * @param int $itemsPerGroup
     * @return \muuska\html\HtmlContent[][]
     */
    public function splitChildren($itemsPerGroup) {
        $result = array();
        $index = 0;
        $currentGroupCount = 0;
        if($itemsPerGroup == 0){
            $itemsPerGroup = 1;
        }
        foreach ($this->children as $child){
            $result[$index][] = $child;
            $currentGroupCount ++;
            if($currentGroupCount == $itemsPerGroup){
                $currentGroupCount = 0;
                $index ++;
            }
        }
        return $result;
    }
    
    /**
     * @return boolean
     */
    public function hasLabel() {
        return !empty($this->label);
    }
    
    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
    
    /**
     * @return \muuska\html\HtmlContent[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}