<?php
namespace muuska\http\redirection;

interface Redirection
{
    /**
     * @param RedirectionInput $input
     * @return string
     */
    public function getFinalUrl(RedirectionInput $input);
    
    /**
     * @param RedirectionInput $input
     * @return string
     */
    public function redirect(RedirectionInput $input);
    
    /**
     * @param RedirectionInput $input
     * @return array
     */
    public function getAjaxParams(RedirectionInput $input);
    
    /**
     * @return string
     */
    public function getType();
    
    /**
     * @return int
     */
    public function getStatusCode();
}
