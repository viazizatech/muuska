<?php
namespace muuska\url\objects;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class ArrayUrl implements ObjectUrl{
    /**
     * @var \muuska\controller\ControllerInput
     */
    protected $controllerInput;
    
    /**
     * @var array
     */
    protected $initialParams;
    
    /**
     * @var string
     */
    protected $defaultUrl;
    
    /**
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param array $initialParams
     * @param string $defaultUrl
     */
    public function __construct(\muuska\controller\ControllerInput $controllerInput,  $initialParams = null, $defaultUrl = null){
        $this->controllerInput = $controllerInput;
        $this->initialParams = $initialParams;
        $this->defaultUrl = $defaultUrl;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\objects\ObjectUrl::createUrl()
     */
    public function createUrl($data, $params = array(), $anchor = '', $mode = null)
    {
        $url = empty($this->defaultUrl) ? 'javascript:;' : $this->defaultUrl;
        if(isset($data['url'])){
            $url = $data['url'];
        }if(isset($data['relativeUrl'])){
            $url = App::getApp()->getBaseUrl().$data['relativeUrl'];
        }elseif(isset($data['controller'])){
            $projectType = isset($data['projectType']) ? $data['projectType'] : ProjectType::APPLICATION;
            $projectName = isset($data['projectName']) ? $data['projectName'] : null;
            $subAppName = isset($data['subAppName']) ? $data['subAppName'] : $this->controllerInput->getSubAppName();
            $action = isset($data['action']) ? $data['action'] : '';
            if(!empty($this->initialParams)){
                if(empty($params)){
                    $params = $this->initialParams;
                }else {
                    $params = array_merge($this->initialParams, $params);
                }
            }
            App::getApp()->createUrl(App::urls()->createUrlCreationInput($subAppName, $this->controllerInput->getLang(), $data['controller'], $action, $params, $projectType, $projectName, $anchor, $this->controllerInput->getVariationTriggers(), $mode));
        }
        return $url;
    }
}