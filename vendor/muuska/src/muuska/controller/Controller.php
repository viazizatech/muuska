<?php
namespace muuska\controller;

interface Controller
{
    /**
     * @return bool
     */
    public function isAuthentificationAlwaysRequired();
    
    /**
     * @return bool
     */
    public function isAccessCheckingAlwaysRequired();
    
    /**
     * @return ControllerResult
     */
    public function onUserNotLogged();
    
    /**
     * @return bool
     */
    public function checkSecurityAccess();
    
    /**
     * @return ControllerResult
     */
    public function executeAction();
    
    /**
     * @return ControllerResult
     */
    public function onAccessFailed();
    
    /**
     * @return ControllerResult
     */
    public function onSecurityFailed();
    
    /**
     * @return ControllerInput
     */
    public function getInput();
}