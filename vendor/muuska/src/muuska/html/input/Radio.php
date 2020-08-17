<?php
namespace muuska\html\input;
class Radio extends Option{
    protected $componentName = 'radio';
    
    /**
     * @var bool
     */
    protected $inline;
	
    /**
     * @return boolean
     */
    public function isInline()
    {
        return $this->inline;
    }

    /**
     * @param boolean $inline
     */
    public function setInline($inline)
    {
        $this->inline = $inline;
    }
}