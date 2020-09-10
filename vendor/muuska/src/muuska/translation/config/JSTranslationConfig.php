<?php
namespace muuska\translation\config;

use muuska\translation\constants\TranslationType;

class JSTranslationConfig implements TranslatorConfig
{
    /**
     * @var string
     */
    protected $path;
    
    /**
     * @var bool
     */
    protected $relatedToTheme;
    
    /**
     * @param string $path
     * @param boolean $relatedToTheme
     */
    public function __construct($path, $relatedToTheme = true){
        $this->setPath($path);
        $this->setRelatedToTheme($relatedToTheme);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getName()
     */
    public function getName()
    {
        return $this->getPath();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getType()
     */
    public function getType()
    {
        return TranslationType::JS;
    }
    
    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * @return boolean
     */
    public function isRelatedToTheme()
    {
        return $this->relatedToTheme;
    }
    
    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
    
    /**
     * @param boolean $relatedToTheme
     */
    public function setRelatedToTheme($relatedToTheme)
    {
        $this->relatedToTheme = $relatedToTheme;
    }
}