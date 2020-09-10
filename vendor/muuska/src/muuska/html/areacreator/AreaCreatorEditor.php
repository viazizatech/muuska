<?php
namespace muuska\html\areacreator;

interface AreaCreatorEditor
{
    /**
     * @param \muuska\html\ContentCreator $contentCreator
     * @param string $defaultPosition
     */
    public function addContentCreator(\muuska\html\ContentCreator $contentCreator, $defaultPosition = null);
    
    /**
     * @param \muuska\html\ContentCreator[] $contentCreators
     */
    public function addContentCreators($contentCreators);
    
    /**
     * @param \muuska\html\ContentCreator[] $contentCreators
     */
    public function setContentCreators($contentCreators);
    
    /**
     * @param string $position
     * @param string $contentName
     */
    public function addContentAtPosition($position, $contentName);
    
    /**
     * @param string $position
     * @param string[] $contentNames
     */
    public function addContentsAtPosition($position, $contentNames);
    
    /**
     * @param string $position
     * @param string[] $contentNames
     */
    public function setContentsAtPosition($position, $contentNames);
    
    /**
     * @param array $contentPositions
     */
    public function setContentPositions($contentPositions);
    
    /**
     * @param array $contentPositions
     */
    public function addContentPositions($contentPositions);
    
    /**
     * @param string $name
     */
    public function removeContentCreator($name);
    
    /**
     * @param string $contentName
     * @param string $position
     */
    public function removeContentFromPosition($contentName, $position);
    
    /**
     * @param string $contentName
     */
    public function removeContentFromAllPositions($contentName);
    
    /**
     * @param string $position
     */
    public function removePosition($position);
}