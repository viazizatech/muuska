<?php
namespace muuska\html\input;

use muuska\html\HtmlElement;

class InputGroup extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'input_group';
    
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $input;
	
	/**
	 * @var \muuska\html\HtmlContent[]
	 */
	protected $leftChildren = array();
	
	/**
	 * @var \muuska\html\HtmlContent[]
	 */
	protected $rightChildren = array();
	
	/**
	 * @param \muuska\html\HtmlContent $input
	 */
	public function __construct(\muuska\html\HtmlContent $input = null) {
	    $this->setInput($input);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('div', $globalConfig, $callerConfig, 'input-group') . $this->renderLeftChildren($globalConfig, $callerConfig, '<div class="input-group-prepend">', '</div>').$this->renderContent($this->input, $globalConfig, $callerConfig, 'input').$this->renderRightChildren($globalConfig, $callerConfig, '<div class="input-group-append">', '</div>').$this->drawEndTag('div', $globalConfig, $callerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderLeftChildren(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return parent::renderContentList($this->leftChildren, $globalConfig, $callerConfig, 'leftChildren', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderRightChildren(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return parent::renderContentList($this->rightChildren, $globalConfig, $callerConfig, 'rightChildren', $prefix, $suffix, $currentCallerConfig);
	}
	
    /**
     * @return \muuska\html\HtmlElement
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return \muuska\html\HtmlContent[]
     */
    public function getLeftChildren()
    {
        return $this->leftChildren;
    }

    /**
     * @return \muuska\html\HtmlContent[]
     */
    public function getRightChildren()
    {
        return $this->rightChildren;
    }

    /**
     * @param \muuska\html\HtmlContent $input
     */
    public function setInput(?\muuska\html\HtmlContent $input)
    {
        $this->input = $input;
    }

    /**
     * @param \muuska\html\HtmlContent[] $leftChildren
     */
    public function setLeftChildren($leftChildren)
    {
        $this->leftChildren = array();
        $this->addLeftChildren($leftChildren);
    }
    
    /**
     * @param \muuska\html\HtmlContent[] $children
     */
    public function addLeftChildren($children)
    {
        if (is_array($children)) {
            foreach ($children as $child) {
                $this->addLeftChild($child);
            }
        }
    }
    
    /**
     * @param \muuska\html\HtmlContent $preview
     * @return \muuska\html\HtmlContent
     */
    public function addLeftChild(\muuska\html\HtmlContent $child){
        return $this->leftChildren[] = $child;
    }

    /**
     * @param \muuska\html\HtmlContent[] $rightChildren
     */
    public function setRightChildren($rightChildren)
    {
        $this->rightChildren = array();
        $this->addRightChildren($rightChildren);
    }
    
    /**
     * @param \muuska\html\HtmlContent[] $children
     */
    public function addRightChildren($children)
    {
        if (is_array($children)) {
            foreach ($children as $child) {
                $this->addRightChild($child);
            }
        }
    }
    
    /**
     * @param \muuska\html\HtmlContent $preview
     * @return \muuska\html\HtmlContent
     */
    public function addRightChild(\muuska\html\HtmlContent $child){
        return $this->rightChildren[] = $child;
    }
}