<?php
namespace muuska\html\areacreator;

class DefaultAreaCreator implements AreaCreator, AreaCreatorEditor
{
    /**
     * @var \muuska\html\ContentCreator[]
     */
    protected $contentCreators;
    
    /**
     * @var array
     */
    protected $contentPositions;
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreator::createContentsByPosition()
     */
    public function createContentsByPosition($position)
    {
        $result = array();
        if(isset($this->contentPositions[$position]) && !empty($this->contentPositions[$position])){
            foreach ($this->contentPositions[$position] as $name) {
                if ($this->hasContentCreator($name)) {
                    $result[$name] = $this->createContentByName($name);
                }
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreator::createContentByName()
     */
    public function createContentByName($name)
    {
        return $this->hasContentCreator($name) ? $this->contentCreators[$name]->createContent() : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreator::hasContentCreator()
     */
    public function hasContentCreator($name)
    {
        return isset($this->contentCreators[$name]);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreator::hasPosition()
     */
    public function hasPosition($position){
        return isset($this->contentPositions[$position]);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreator::getContentPosition()
     */
    public function getContentPosition($contentName){
        $result = null;
        if (is_array($this->contentPositions)) {
            foreach ($this->contentPositions as $key => $contents) {
                if(in_array($contentName, $contents)){
                    $result = $key;
                    break;
                }
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreator::isContentRegisteredAtPosition()
     */
    public function isContentRegisteredAtPosition($contentName, $position){
        return (isset($this->contentPositions[$position]) && in_array($contentName, $this->contentPositions));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreator::isContentPositioned()
     */
    public function isContentPositioned($contentName){
        return !empty($this->getContentPosition($contentName));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::addContentCreator()
     */
    public function addContentCreator(\muuska\html\ContentCreator $contentCreator, $defaultPosition = null)
    {
        $name = $contentCreator->getName();
        $this->contentCreators[$name] = $contentCreator;
        if(!empty($defaultPosition) && !$this->isContentPositioned($name)){
            $this->addContentAtPosition($defaultPosition, $name);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::addContentCreators()
     */
    public function addContentCreators($contentCreators)
    {
        if (is_array($contentCreators)) {
            foreach ($contentCreators as $contentCreator) {
                $this->addContentCreator($contentCreator);
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::setContentCreators()
     */
    public function setContentCreators($contentCreators)
    {
        $this->contentCreators = array();
        $this->addContentCreators($contentCreators);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::addContentAtPosition()
     */
    public function addContentAtPosition($position, $name)
    {
        if(!$this->isContentRegisteredAtPosition($name, $position)){
            $this->contentPositions[$position][] = $name;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::addContentsAtPosition()
     */
    public function addContentsAtPosition($position, $names)
    {
        if (is_array($names)) {
            foreach ($names as $name) {
                $this->addContentAtPosition($position, $name);
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::setContentsAtPosition()
     */
    public function setContentsAtPosition($position, $names)
    {
        $this->contentPositions[$position] = array();
        $this->addContentsAtPosition($position, $names);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::setContentPositions()
     */
    public function setContentPositions($contentPositions)
    {
        $this->contentPositions = array();
        $this->addContentPositions($contentPositions);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::addContentPositions()
     */
    public function addContentPositions($contentPositions)
    {
        if (is_array($contentPositions)) {
            foreach ($contentPositions as $position => $names) {
                $this->addContentsAtPosition($position, $names);
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::removeContentCreator()
     */
    public function removeContentCreator($name){
        if ($this->hasContentCreator($name)) {
            unset($this->contentCreators[$name]);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::removeContentFromPosition()
     */
    public function removeContentFromPosition($contentName, $position){
        if (is_array($this->contentPositions)) {
            foreach ($this->contentPositions as $key => $names) {
                if($key === $position){
                    foreach ($names as $index => $value) {
                        if($value === $contentName){
                            unset($this->contentPositions[$key][$index]);
                            break;
                        }
                    }
                    break;
                }
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::removeContentFromAllPositions()
     */
    public function removeContentFromAllPositions($contentName){
        if (is_array($this->contentPositions)) {
            foreach ($this->contentPositions as $key => $names) {
                foreach ($names as $index => $value) {
                    if($value === $contentName){
                        unset($this->contentPositions[$key][$index]);
                        break;
                    }
                }
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\areacreator\AreaCreatorEditor::removePosition()
     */
    public function removePosition($position){
        if ($this->hasPosition($position)) {
            unset($this->contentPositions[$position]);
        }
    }
}