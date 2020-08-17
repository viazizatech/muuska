<?php
namespace muuska\util\setup;

class SetupInput
{
    /**
     * @var \muuska\controller\ControllerInput
     */
    protected $controllerInput;
    
	/**
	 * @var \muuska\asset\AssetSetter
	 */
	protected $assetSetter;
	
	/**
	 * @var \muuska\url\ControllerUrlCreator
	 */
	protected $controllerUrlCreator;
	
	public function __construct(\muuska\controller\ControllerInput $controllerInput, \muuska\url\ControllerUrlCreator $controllerUrlCreator, \muuska\asset\AssetSetter $assetSetter) {
	    $this->controllerInput = $controllerInput;
	    $this->assetSetter = $assetSetter;
	    $this->controllerUrlCreator = $controllerUrlCreator;
	}
	
    /**
     * @return \muuska\controller\ControllerInput
     */
    public function getControllerInput()
    {
        return $this->controllerInput;
    }

    /**
     * @return \muuska\asset\AssetSetter
     */
    public function getAssetSetter()
    {
        return $this->assetSetter;
    }

    /**
     * @return \muuska\url\ControllerUrlCreator
     */
    public function getControllerUrlCreator()
    {
        return $this->controllerUrlCreator;
    }
}