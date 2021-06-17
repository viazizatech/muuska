<?php
namespace myapp\controller\front;

use muuska\controller\AbstractController;
use myapp\model\Membre;
use myapp\constants\Accessibility;
use myapp\model\EmpruntDefinition;
use muuska\controller\CrudController;
use muuska\constants\ExternalFieldEditionType;
use myapp\model\EpargneDefinition;
use myapp\model\MembreDefinition;
use myapp\model\AssociationDefinition;
use muuska\html\ChildrenContainer;
use muuska\util\App;
use muuska\asset\constants\AssetType;
use muuska\html\constants\ButtonStyle;
use muuska\http\constants\RedirectionType;

class AssoModelController   extends  AbstractController
{
    
    
    protected function processDefault()
    {
       
        
        
            $form = App::htmls()->createForm();
            $nomInput = App::htmls()->createHtmlInput('text', 'my_input',$this->l('Association'));
            $nomField = App::htmls()->createFormField('nom', null,  $nomInput);
            $form->addChild($nomField);  
           //ajout du siege  
            $siegeInput = App::htmls()->createHtmlInput('text', '',$this->l('Siege'));
            $siegeField = App::htmls()->createFormField('siege', null,  $siegeInput);
            $form->addChild($siegeField);
            //ajout de la devise
            $deviseInput = App::htmls()->createHtmlInput('text', '',$this->l('Devise'));
            $siegeField = App::htmls()->createFormField('Devise', null,  $deviseInput);
            $form->addChild($siegeField);
             //ajout du contact
             $telephoneInput = App::htmls()->createHtmlInput('text', '',$this->l('Telephone'));
            $telephoneField = App::htmls()->createFormField('telephone', null,  $telephoneInput);
            $form->addChild($telephoneField);          
            //nom de l'amin
            $adminInput = App::htmls()->createHtmlInput('text', '',$this->l('Administrateur(nom)'));
            $adminField = App::htmls()->createFormField('admin', null,  $adminInput);
            $form->addChild($adminField);
            //prenom de l'amin
            $admin_prenomInput = App::htmls()->createHtmlInput('text', '',$this->l('Administrateur(prenom)'));
            $adminField = App::htmls()->createFormField('padmin', null,  $admin_prenomInput);
            $form->addChild($adminField);
            //mot de passe
            $passwordInput = App::htmls()->createHtmlInput('password', 'password', null, $this->l('Password'));
                $passwordField = App::htmls()->createFormField('password', null, $passwordInput);
                $form->addChild($passwordField);
    
                //mot de passe
            $passwordInput = App::htmls()->createHtmlInput('password', 'password', null, $this->l('confirmer votre Password'));
                $passwordField = App::htmls()->createFormField('password', null, $passwordInput);
                $form->addChild($passwordField);
                //email
                $emailInput = App::htmls()->createHtmlInput('text', '',$this->l('Email'));
                $emailField = App::htmls()->createFormField('email', null, $emailInput);
                $form->addChild($emailField);
                //status
                $statusInput = App::htmls()->createHtmlInput('text', '',$this->l('Status'));
                $statusField = App::htmls()->createFormField('status', null, $statusInput );
                $form->addChild($statusField);
                //status
                $logoInput = App::htmls()->createHtmlInput('text', '',$this->l('Logo'));
                $logoField = App::htmls()->createFormField('logo', null, $logoInput );
                $form->addChild($logoField);
                $submitButton = App::htmls()->createButton(App::createHtmlString($this->l('Creer votre association')), 'submit', null, ButtonStyle::DANGER);
                $form->setSubmit($submitButton);
                $this->result->setContent($form);
    
        
    }
    
   
    
   
}
