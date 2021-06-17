<?php
namespace muuska\controller\Front;

use muuska\controller\AbstractController;
use muuska\util\App;
use muuska\asset\constants\AssetType;
use muuska\html\constants\ButtonStyle;
use muuska\http\constants\RedirectionType;

class AssociationCreateController extends AbstractController
{	protected $id;
    protected $profilId;
    protected $accessibility;
    protected $nom_assoc;
    protected $siege;
    protected $telephone;
    protected $email_respon;
	protected $logo;
    protected $nom_admin;	
    protected $prenom_admin;	
    protected $devise;
    protected $mot_pass;
    protected $con_mot_pass;
    protected $status;	
	protected function processDefault()
    {
        $this->input->getResponse()->addHeader('login/create', 'true');
        $createForm = true;
        $loginError = null;
        $passwordError = null;
        $formError = null;
        $loginValue = null;
        $stayConnected = 0;
        if($this->input->hasParam('submitted')){
            $loginValue = $this->input->getParam('login');
            $password = $this->input->getParam('password');
            $stayConnected = (int)$this->input->getParam('stay_connected');
            $noError = true;
            if(empty($loginValue)){
                $loginError = $this->l('Login is required');
                $noError = false;
            }
            if(empty($password)){
                $passwordError = $this->l('Password is required');
                $noError = false;
            }
            if($noError){
                $currentUser = $this->input->getCurrentUser();
                if($currentUser instanceof \muuska\security\DefaultCurrentUser){
                    $encryptedPassword = $currentUser->encryptPassword($password);
                    $auth = $currentUser->getAuthentificationByLogin($loginValue, true, $encryptedPassword, true);
                    if($auth !== null){
                        $currentUser->logIn($this->input->getRequest(), $this->input->getVisitorInfoRecorder(), $auth->getId(), $encryptedPassword, $stayConnected);
                        $backUrl = $this->getBackUrl();
                        $redirection = null;
                        if(!empty($backUrl)){
                            $redirection = App::https()->createDirectRedirection(RedirectionType::BACK_TO_CALLER, $backUrl);
                        }else{
                            $redirection = App::https()->createDynamicRedirection(RedirectionType::HOME);
                        }
                        $this->result->setRedirection($redirection);
                    }else{
                        $formError = $this->l('Invalid access');
                    }
                }else{
                    $formError = $this->l('No login service found');
                }
            }
            if(!$this->result->hasRedirection()){
                $createForm = true;
            }
        }
        if($createForm){
            $form = App::htmls()->createForm($this->urlCreator->createDefaultUrl());
            $form->setRenderer($this->getThemeTemplate('form/login_form'));
            $loginInput = App::htmls()->createHtmlInput('text', 'login', $loginValue, $this->l('Login'));
            $loginField = App::htmls()->createFormField('login', null, $loginInput);
            $loginField->setRenderer($this->getThemeTemplate('form/login_field'));
            $loginField->setError($loginError);
            $form->addChild($loginField);
            
            $passwordInput = App::htmls()->createHtmlInput('password', 'password', null, $this->l('Password'));
            $passwordField = App::htmls()->createFormField('password', null, $passwordInput);
            $passwordField->setRenderer($this->getThemeTemplate('form/login_field'));
            $passwordField->setError($passwordError);
            $form->addChild($passwordField);
            $rememberMe = App::htmls()->createCheckbox('stay_connected', $this->l('Remember me'), 1, $stayConnected);
            $areaCreator = App::htmls()->createDefaultAreaCreator();
            $areaCreator->addContentCreator($rememberMe, 'extraLeft');
            $submitButton = App::htmls()->createButton(App::createHtmlString($this->l('Sign in')), 'submit', null, ButtonStyle::BRAND);
            $form->setSubmit($submitButton);
            $form->setAreaCreator($areaCreator);
            $form->setErrorText($formError);
            $this->result->setContent($form);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\AbstractController::formatHtmlPage()
     */
    protected function formatHtmlPage(\muuska\controller\event\ControllerPageFormatingEvent $event, $fireFormattingEvent = true)
    {
        $title = App::createHtmlString($this->l('Sign In To Admin'), 'loginTitle');
        $event->addContentCreator($title);
        $event->setPageRenderer($this->getThemeTemplate('page/login_admin'));
        $this->input->getTheme()->createHtmlImage('fsfsfsf');
        $this->addThemeAsset(AssetType::CSS, 'login-3.css');
        parent::formatHtmlPage($event, false);
    }
    
    protected function getBackUrl() {
        ;
    }

    
}