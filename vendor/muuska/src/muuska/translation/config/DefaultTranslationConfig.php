<?php
namespace muuska\translation\config;

class DefaultTranslationConfig implements TranslatorConfig
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @param string $type
     * @param string $name
     */
    public function __construct($type, $name) {
        $this->setName($name);
        $this->setType($type);
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}