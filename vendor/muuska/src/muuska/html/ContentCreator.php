<?php
namespace muuska\html;

interface ContentCreator
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return \muuska\html\HtmlContent
     */
    public function createContent();
}