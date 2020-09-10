<?php
namespace muuska\html;

abstract class AbstractChildWrapper extends HtmlElement{
    /**
     * @var \muuska\html\HtmlContent[]
     */
    protected $children = array();
    
    /**
     * @param \muuska\html\HtmlContent[] $children
     */
    public function __construct($children = array()){
        $this->setChildren($children);
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
     * @return boolean
     */
    public function hasChildren(){
        return !empty($this->children);
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
     * @return \muuska\html\HtmlContent[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}