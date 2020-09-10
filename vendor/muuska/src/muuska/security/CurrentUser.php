<?php
namespace muuska\security;

interface CurrentUser
{
    /**
     * @return AuthentificationInfo
     */
    public function getAuthentificationInfo();
    
    /**
     * @param ResourceTree $ressourceTree
     * @return bool
     */
    public function checkAccess(ResourceTree $resourceTree);
    
    /**
     * @param string $lang
     * @param bool $withParents
     * @return GroupInfo[]
     */
    public function getGroups($lang, $withParents = true);
    
    /**
     * @param \muuska\http\Request $request
     * @param \muuska\http\Response $response
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     */
    public function logOut(\muuska\http\Request $request, \muuska\http\Response $response, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder);
    
    /**
     * @return bool
     */
    public function isLogged();
    
    /**
     * @return bool
     */
    public function hasPreferredLang();
    
    /**
     * @return string
     */
    public function getPreferredLang();
    
    /**
     * @return bool
     */
    public function hasPersonInfo();
    
    /**
     * @return PersonInfo
     */
    public function getPersonInfo();
}