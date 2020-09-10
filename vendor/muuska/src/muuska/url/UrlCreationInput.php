<?php
namespace muuska\url;

class UrlCreationInput
{
    /**
     * @var string
     */
    protected $subAppName;
    
    /**
     * @var string
     */
    protected $lang;
    
    /**
     * @var string
     */
    protected $controllerName;
    
    /**
     * @var string
     */
    protected $action;
    
    /**
     * @var array
     */
    protected $params;
    
    /**
     * @var string
     */
    protected $projectType;
    
    /**
     * @var string
     */
    protected $projectName;
    
    /**
     * @var string
     */
    protected $anchor;
    
    /**
     * @var \muuska\util\variation\VariationTrigger[]
     */
    protected $variationTriggers;
    
    /**
     * @var int
     */
    protected $mode;
    
    /**
     * @param string $subAppName
     * @param string $lang
     * @param string $controllerName
     * @param string $action
     * @param array $params
     * @param string $projectType
     * @param string $projectName
     * @param string $anchor
     * @param \muuska\util\variation\VariationTrigger[] $variationTriggers
     * @param int $mode
     */
    public function __construct($subAppName, $lang, $controllerName, $action = null, $params = array(), $projectType = null, $projectName = null, $anchor = '', $variationTriggers = array(), $mode = null){
        $this->subAppName = $subAppName;
        $this->lang = $lang;
        $this->controllerName = $controllerName;
        $this->action = $action;
        $this->params = $params;
        $this->projectType = $projectType;
        $this->projectName = $projectName;
        $this->anchor = $anchor;
        $this->variationTriggers = $variationTriggers;
        $this->mode = $mode;
    }
    
    /**
     * @return string
     */
    public function getSubAppName()
    {
        return $this->subAppName;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @return string
     */
    public function getAnchor()
    {
        return $this->anchor;
    }

    /**
     * @return \muuska\util\variation\VariationTrigger[]
     */
    public function getVariationTriggers()
    {
        return $this->variationTriggers;
    }
    
    /**
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }
}