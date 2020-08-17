<?php
namespace muuska\security;

class ResourceTree
{
    /**
     * @var string
     */
    protected $code;
    
    /**
     * @var ResourceTree
     */
    protected $subResourceTree;
    
    /**
     * @param string $code
     * @param ResourceTree $subResourceTree
     */
    public function __construct($code, ResourceTree $subResourceTree = null){
        $this->code = $code;
        $this->subResourceTree = $subResourceTree;
    }
    
    /**
     * @return bool
     */
    public function hasSubResourceTree(){
        return ($this->subResourceTree !== null);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return \muuska\security\ResourceTree
     */
    public function getSubResourceTree()
    {
        return $this->subResourceTree;
    }
}