<?php
namespace muuska\http\redirection;

class RedirectionInput
{
    /**
     * @var \muuska\http\Response
     */
    protected $response;
    
    /**
     * @var \muuska\url\ControllerUrlCreator
     */
    protected $controllerUrlCreator;
    
    /**
     * @var \muuska\http\VisitorInfoRecorder
     */
    protected $visitorInfoRecorder;
    
    /**
     * @var bool
     */
    protected $alertInfoRecordingEnabled;
    
    /**
     * @var string
     */
    protected $alertRecorderKey;
    
    /**
     * @var bool
     */
    protected $backInfoRecordingEnabled;
    
    /**
     * @var string
     */
    protected $backRecorderKey;
    
    /**
     * @param \muuska\http\Response $response
     * @param \muuska\url\ControllerUrlCreator $controllerUrlCreator
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @param bool $alertInfoRecordingEnabled
     * @param string $alertRecorderKey
     * @param bool $backInfoRecordingEnabled
     * @param string $backRecorderKey
     */
    public function __construct(\muuska\http\Response $response, \muuska\url\ControllerUrlCreator $controllerUrlCreator, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder = null, $alertInfoRecordingEnabled = false, $alertRecorderKey = null, $backInfoRecordingEnabled = false, $backRecorderKey = null) {
        $this->response = $response;
        $this->controllerUrlCreator = $controllerUrlCreator;
        $this->visitorInfoRecorder = $visitorInfoRecorder;
        $this->alertInfoRecordingEnabled = $alertInfoRecordingEnabled;
        $this->alertRecorderKey = $alertRecorderKey;
        $this->backInfoRecordingEnabled = $backInfoRecordingEnabled;
        $this->backRecorderKey = $backRecorderKey;
    }
    
    /**
     * @return bool
     */
    public function hasVisitorInfoRecorder(){
        return ($this->visitorInfoRecorder !== null);
    }
    
    /**
     * @return \muuska\http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return \muuska\url\ControllerUrlCreator
     */
    public function getControllerUrlCreator()
    {
        return $this->controllerUrlCreator;
    }

    /**
     * @return \muuska\http\VisitorInfoRecorder
     */
    public function getVisitorInfoRecorder()
    {
        return $this->visitorInfoRecorder;
    }

    /**
     * @return string
     */
    public function isAlertInfoRecordingEnabled()
    {
        return $this->alertInfoRecordingEnabled;
    }

    /**
     * @return string
     */
    public function getAlertRecorderKey()
    {
        return $this->alertRecorderKey;
    }

    /**
     * @return string
     */
    public function isBackInfoRecordingEnabled()
    {
        return $this->backInfoRecordingEnabled;
    }

    /**
     * @return string
     */
    public function getBackRecorderKey()
    {
        return $this->backRecorderKey;
    }
}
