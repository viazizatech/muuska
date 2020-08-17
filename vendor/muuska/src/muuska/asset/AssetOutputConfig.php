<?php
namespace muuska\asset;

class AssetOutputConfig
{
    /**
     * @var int
     */
    protected $mode;
    
    /**
     * @var bool
     */
    protected $minified;
    
    /**
     * @var bool
     */
    protected $onlyContentEnabled;
    
    /**
     * @param int $mode
     * @param bool $onlyContentEnabled
     * @param bool $minified
     */
    public function __construct($mode, $onlyContentEnabled = false, $minified = false){
        $this->setMode($mode);
        $this->setOnlyContentEnabled($onlyContentEnabled);
        $this->setMinified($minified);
    }
    
    /**
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return bool
     */
    public function isMinified()
    {
        return $this->minified;
    }

    /**
     * @param int $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }
    
    /**
     * @param bool $minified
     */
    public function setMinified($minified)
    {
        $this->minified = $minified;
    }
    /**
     * @return bool
     */
    public function isOnlyContentEnabled()
    {
        return $this->onlyContentEnabled;
    }

    /**
     * @param bool $onlyContentEnabled
     */
    public function setOnlyContentEnabled($onlyContentEnabled)
    {
        $this->onlyContentEnabled = $onlyContentEnabled;
    }
}
