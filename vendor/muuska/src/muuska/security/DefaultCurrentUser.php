<?php
namespace muuska\security;

use muuska\constants\operator\Operator;
use muuska\security\constants\ResourceAccessRule;
use muuska\util\App;

class DefaultCurrentUser implements CurrentUser
{
    /**
     * @var string
     */
    protected $subAppName;
    
    /**
     * @var \muuska\dao\DAOFactory
     */
    protected $daoFactory;
    
    /**
     * @var bool
     */
    protected $logged;
    
    /**
     * @var \muuska\security\model\AuthentificationModel
     */
    protected $authentificationInfo;
    
    /**
     * @var PersonInfo
     */
    protected $personInfo;
    
    /**
     * @var array
     */
    private static $allResources = array();
    
    /**
     * @var array
     */
    private $tmpData;
    
    /**
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param string $subAppName
     * @param \muuska\http\Request $request
     * @param \muuska\http\Response $response
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @param PersonInfoResolver $personInfoResolver
     */
    public function __construct(\muuska\dao\DAOFactory $daoFactory, $subAppName, \muuska\http\Request $request, \muuska\http\Response $response, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, PersonInfoResolver $personInfoResolver = null){
        $this->daoFactory = $daoFactory;
        $this->subAppName = $subAppName;
        $this->initUser($request, $response, $visitorInfoRecorder, $personInfoResolver);
    }
    
    /**
     * @param \muuska\http\Request $request
     * @param \muuska\http\Response $response
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @param PersonInfoResolver $personInfoResolver
     */
    protected function initUser(\muuska\http\Request $request, \muuska\http\Response $response, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, PersonInfoResolver $personInfoResolver = null){
        $logUserOut = false;
        if ($visitorInfoRecorder->hasValue('lastActivity')) {
            if (((int)$visitorInfoRecorder->getValue('lastActivity') + 900) < time()) {
                $logUserOut = true;
            } else {
                $visitorInfoRecorder->setValue('lastActivity', time());
            }
        }
        if(App::getApp()->isInstalled() && !$logUserOut){
            $authId = (int)$visitorInfoRecorder->getValue('authId');
            $password = $visitorInfoRecorder->getValue('password');
            $checkIp = App::getApp()->getSubApplicationConfig($this->subAppName)->getBool('check_saved_ip_for_login', false);
            if(!empty($authId) && !empty($password) && (!$checkIp || ($visitorInfoRecorder->getValue('remoteAddress') === $request->getRemoteAddr()))){
                $daoAuth = $this->daoFactory->getDAO(App::securities()->getAuthentificationDefinitionInstance());
                $selectionConfig = $daoAuth->createSelectionConfig();
                $selectionConfig->setOnlyActive(true);
                $selectionConfig->addRestrictionFieldFromParams('id', $authId);
                $selectionConfig->addRestrictionFieldFromParams('password', $password);
                $selectionConfig->addRestrictionFieldFromParams('subAppName', $this->subAppName);
                $this->authentificationInfo = $daoAuth->getUniqueModel($selectionConfig, false);
                if($this->authentificationInfo !== null){
                    $this->logged = true;
                }
            }
            if(!empty($authId) && !$this->logged){
                $logUserOut = true;
            }
        }
        if($logUserOut){
            $this->logOut($request, $response, $visitorInfoRecorder);
        }
    }
    
    /**
     * @param \muuska\http\Request $request
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @param string $authentificationId
     * @param string $password
     * @param boolean $stayLoggedIn
     */
    public function logIn(\muuska\http\Request $request, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, $authentificationId, $password, $stayLoggedIn = false){
        $visitorInfoRecorder->setValue('authId', $authentificationId);
        $visitorInfoRecorder->setValue('password', $password);
        $visitorInfoRecorder->setValue('remoteAddress', $request->getRemoteAddr());
        
        if (!$stayLoggedIn) {
            $visitorInfoRecorder->setValue('lastActivity', time());
        }
    }
    
    /**
     * @param string $login
     * @param boolean $checkPassword
     * @param string $password
     * @param boolean $onlyActive
     * @return \muuska\security\model\AuthentificationModel
     */
    public function getAuthentificationByLogin($login, $checkPassword = true, $password = null, $onlyActive = true)
    {
        $authentification = null;
        if(!empty($login) && (!$checkPassword || !empty($password))){
            $daoAuth = $this->daoFactory->getDAO(App::securities()->getAuthentificationDefinitionInstance());
            $selectionConfig = $daoAuth->createSelectionConfig();
            $selectionConfig->addRestrictionFieldFromParams('subAppName', $this->subAppName);
            $selectionConfig->addRestrictionFieldFromParams('login', $login);
            if($checkPassword){
                $selectionConfig->addRestrictionFieldFromParams('password', $password);
            }
            $selectionConfig->setOnlyActive($onlyActive);
            
            $authentification = $daoAuth->getUniqueModel($selectionConfig, false);
        }
        return $authentification;
    }
    
    /**
     * @param string $password
     * @return string
     */
    public function encryptPassword($password)
    {
        return App::getTools()->encrypt($password);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\CurrentUser::getAuthentificationInfo()
     */
    public function getAuthentificationInfo(){
        return ($this->authentificationInfo !== null) ? clone $this->authentificationInfo : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\CurrentUser::logOut()
     */
    public function logOut(\muuska\http\Request $request, \muuska\http\Response $response, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder){
        $this->authentificationInfo = null;
        $this->personInfo = null;
        $visitorInfoRecorder->removeAllValues();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\CurrentUser::isLogged()
     */
    public function isLogged(){
        return $this->logged;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\CurrentUser::hasPreferredLang()
     */
    public function hasPreferredLang(){
        return !empty($this->getPreferredLang());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\CurrentUser::getPreferredLang()
     */
    public function getPreferredLang(){
        return ($this->authentificationInfo !== null) ? $this->authentificationInfo->getPreferredLang() : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\CurrentUser::hasPersonInfo()
     */
    public function hasPersonInfo(){
        return ($this->getPersonInfo() !== null);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\CurrentUser::getPersonInfo()
     */
    public function getPersonInfo(){
        return $this->personInfo;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\CurrentUser::getGroups()
     */
    public function getGroups($lang, $withParents = true){
        return $this->getGroupCollection($withParents, true, $lang);
    }
    
    /**
     * @param boolean $withParents
     * @param boolean $langEnabled
     * @param string $lang
     * @return \muuska\model\ModelCollection
     */
    protected function getGroupCollection($withParents = true, $langEnabled = false, $lang = null){
        $key = (int)$withParents . '_' . (int)$langEnabled . '_'.$lang;
        if(!isset($this->tmpData['groups']) || !isset($this->tmpData['groups'][$key])){
            $dao = $this->daoFactory->getDAO(App::securities()->getAuthentificationGroupDefinitionInstance());
            $selectionConfig = $dao->createSelectionConfig();
            $selectionConfig->setLangEnabled($langEnabled);
            if($langEnabled && !empty($lang)){
                $selectionConfig->setLang($lang);
            }
            $selectionConfig->setRestrictionFieldParams('authentificationId', $this->authentificationInfo->getId());
            $selectionConfig->addRestrictionFieldFromParams('groupId', $this->subAppName, null, null, true, 'subAppName');
            $groupResult = $dao->getData($selectionConfig)->getAssociatedCollectionFromField('groupId');
            
            if($withParents){
                $groupDefinition = App::securities()->getGroupDefinitionInstance();
                $groupDao = $this->daoFactory->getDAO($groupDefinition);
                $parentCollection = App::models()->createModelCollection($groupDefinition);
                foreach($groupResult as $groupTmp){
                    $newSelectionConfig = $dao->createSelectionConfig();
                    $selectionConfig->setLangEnabled($langEnabled);
                    if($langEnabled && !empty($lang)){
                        $selectionConfig->setLang($lang);
                    }
                    $parentCollection->addCollection($groupDao->getParents($groupTmp, $newSelectionConfig));
                }
                $groupResult->addCollection($parentCollection);
            }
            $this->tmpData['groups'][$key] = $groupResult;
        }
        
        return $this->tmpData['groups'][$key];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\CurrentUser::checkAccess()
     */
    public function checkAccess(ResourceTree $resourceTree){
        $result = false;
        if($this->authentificationInfo !== null){
            $this->loadAccess();
            $result = $this->checkAuthAccess($resourceTree, self::$allResources);
        }
        return $result;
    }
    
    protected function loadAccess()
    {
        if (!isset($this->tmpData['accessList'])) {
            $this->tmpData['accessList'] = array();
            if($this->authentificationInfo !== null){
                $dao = $this->daoFactory->getDAO(App::securities()->getAuthentificationAccessDefinitionInstance());
                $selectionConfig = $dao->createSelectionConfig();
                $selectionConfig->addRestrictionFieldFromParams('authentificationId', $this->authentificationInfo->getId());
                $this->tmpData['accessList'] = $dao->getData($selectionConfig)->getArrayValuesFromField('resourceId');
                
                $groupCollection = $this->getGroupCollection(true, false);
                if(!$groupCollection->isEmpty()){
                    $groupAccessDao = $this->daoFactory->getDAO(App::securities()->getGroupAccessDefinitionInstance());
                    $selectionConfig = $dao->createSelectionConfig();
                    $selectionConfig->addRestrictionFieldFromParams('groupId', $groupCollection->getArrayValuesFromField('id'), Operator::IN_LIST);
                    $this->tmpData['accessList'] = array_merge($this->tmpData['accessList'], $groupAccessDao->getData($selectionConfig)->getArrayValuesFromField('resourceId'));
                }
            }
        }
    }
    
    /**
     * @param ResourceTree $resourceTree
     * @param array $parentResource
     * @return bool
     */
    protected function checkAuthAccess(ResourceTree $resourceTree, array &$parentResource)
    {
        $result = true;
        if(!isset($parentResource['children'])){
            $parentResourceId = isset($parentResource['id']) ? $parentResource['id'] : null;
            $parentResource['children'] = array();
            $dao = $this->daoFactory->getDAO(App::securities()->getResourceDefinitionInstance());
            $selectionConfig = $dao->createSelectionConfig();
            $selectionConfig->setLangEnabled(false);
            $selectionConfig->addRestrictionFieldFromParams('parentId', $parentResourceId);
            $list = $dao->getData($selectionConfig);
            foreach ($list as $resourceModel) {
                $parentResource['children'][$resourceModel->getCode()] = array('id' => $resourceModel->getId(), 'accessRule' => $resourceModel->getAccessRule());
            }
        }
        $key = $resourceTree->getCode();
        
        if(isset($parentResource['children'][$key])){
            $accessRule = $parentResource['children'][$key]['accessRule'];
            if($this->authentificationInfo->isSuperUser() || ($accessRule == ResourceAccessRule::NONE)){
                $result = true;
            }elseif($accessRule == ResourceAccessRule::AUTHORIZATION){
                $result = in_array($parentResource['children'][$key]['id'], $this->tmpData['accessList']);
            }
            if($result && $resourceTree->hasSubResourceTree()){
                $result = $this->checkAuthAccess($resourceTree->getSubResourceTree(), $parentResource['children'][$key]);
            }
        }
        return $result;
    }
}