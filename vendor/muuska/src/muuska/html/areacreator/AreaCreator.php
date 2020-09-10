<?php
namespace muuska\html\areacreator;

interface AreaCreator
{
    /**
     * @param string $name
     * @return \muuska\html\HtmlContent
     */
    public function createContentByName($name);
	
    /**
     * @param string $name
     * @return \muuska\html\HtmlContent[]
     */
	public function createContentsByPosition($position);
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasContentCreator($name);
    
    /**
     * @param string $position
     * @return bool
     */
    public function hasPosition($position);
    
    /**
     * @param string $contentName
     * @return string
     */
    public function getContentPosition($contentName);
    
    /**
     * @param string $contentName
     * @param string $position
     * @return bool
     */
    public function isContentRegisteredAtPosition($contentName, $position);
    
    /**
     * @param string $contentName
     * @return bool
     */
    public function isContentPositioned($contentName);
}