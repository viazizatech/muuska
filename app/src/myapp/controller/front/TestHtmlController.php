<?php
namespace myapp\controller\front;

use muuska\controller\AbstractController;
use muuska\html\constants\ButtonStyle;
use muuska\html\constants\IconPosition;
use muuska\html\form;
use muuska\html\HtmlContent;
use muuska\util\App;
use myapp\option\AccessibilityProvider;
use muuska\constants\FileTypeConst;
use myapp\model\AssociationDefinition;

class TestHtmlController extends AbstractController
{
    protected function processIcon()
    {
        $icon = App::htmls()->createClassIcon('fa fa-paper-plane');
        $this->result->setContent($icon);
    }
    
    protected function processImage()
    {
        $image = App::htmls()->createHtmlImage('http://localhost/test/img.jpg', 'My image');
        $this->result->setContent($image);
    }
    
    protected function processRelativeImage()
    {
        $resolver = App::getApp()->getAssetResolver();
        $image = App::htmls()->createRelativeHtmlImage($resolver, 'test.jpg', 'My image');
        $this->result->setContent($image);
    }
    
    protected function processButton()
    {
        $button = App::htmls()->createButton(App::createHtmlString('My button'));
        $this->result->setContent($button);
    }
    
    protected function processButtonIcon()
    {
        $icon = App::createFAIcon('plus');
        $button = App::htmls()->createButton(App::createHtmlString('My button'), 'button', $icon);
        $this->result->setContent($button);
    }
    
    protected function processButtonRightIcon()
    {
        $icon = App::createFAIcon('plus');
        $button = App::htmls()->createButton(App::createHtmlString('My button'), 'button', $icon);
        $button->setIconPosition(IconPosition::RIGHT);
        $this->result->setContent($button);
    }
    
    protected function processButtonStyle()
    {
        $button = App::htmls()->createButton(App::createHtmlString('My button'), 'button', null, ButtonStyle::PRIMARY);
        $this->result->setContent($button);
    }
    
    protected function processLink()
    {
        $link = App::htmls()->createHtmlLink(App::createHtmlString('My link', '#'));
        $this->result->setContent($link);
    }
    
    protected function processLinkButton()
    {
        $icon = App::createFAIcon('plus');
        $link = App::htmls()->createHtmlLink(App::createHtmlString('My link'), '#', $icon, '', true, ButtonStyle::PRIMARY);
        $this->result->setContent($link);
    }
    
    protected function processInput()
    {
        $input = App::htmls()->createHtmlInput('text', 'my_input', 'My Value', 'My placeholder');
        $this->result->setContent($input);
    }
    
    protected function processTextarea()
    {
        $textarea = App::htmls()->createTextarea('my_textarea', 'My Value', 10);
        $this->result->setContent($textarea);
    }
    
    protected function processSelect()
    {
        $optionProvider = new AccessibilityProvider($this->input->getLang());
        $select = App::htmls()->createSelect('my_select', $optionProvider, null, true);
        $this->result->setContent($select);
    }
    
    protected function processCheckbox()
    {
        $checkbox = App::htmls()->createCheckbox('my_checkbox', 'My checkbox', 'my_value', true);
        $this->result->setContent($checkbox);
    }
    
    protected function processRadio()
    {
        $array = array(
            'value1' => 'Value 1',
            'value2' => 'Value 2',
            'value3' => 'Value 3',
        );
        $optionProvider = App::options()->createKeyValueOptionProvider($array);
        $radio = App::htmls()->createRadio('my_radio', $optionProvider, 'value2');
        $this->result->setContent($radio);
    }
    
    protected function processRadioInline()
    {
        $array = array(
            'value1' => 'Value 1',
            'value2' => 'Value 2',
            'value3' => 'Value 3',
        );
        $optionProvider = App::options()->createKeyValueOptionProvider($array);
        $radio = App::htmls()->createRadio('my_radio', $optionProvider, 'value2');
        $radio->setInline(true);
        $this->result->setContent($radio);
    }
    
    protected function processRadioSwitch()
    {
        $optionProvider = App::options()->createBoolProvider($this->input->getLang());
        $radio = App::htmls()->createRadioSwitch('my_radio', $optionProvider, 1);
        $radio->setInline(true);
        $this->result->setContent($radio);
    }
    
    protected function processRichTextEditor()
    {
        $editor = App::htmls()->createRichTextEditor('my_editor', 'My text');
        $this->result->setContent($editor);
    }
    
    protected function processFileUpload()
    {
        $upload = App::htmls()->createFileUpload($this->urlCreator->createUrl('upload'), false, '');
        $upload->setDeleteUrl($this->urlCreator->createUrl('delete-upload'));
        $upload->setPreviewTemplate(App::htmls()->createUploadPreview('my_upload', null, '', false, true));
        $allowedExtensions = App::getFileTools()->getExtensionsByFileType(FileTypeConst::IMAGE);
        $upload->setAllowedExtensions($allowedExtensions);
        $upload->setAccept('image/*');
        $this->result->setContent($upload);
    }
    
    protected function processFileUploadPreview()
    {
        $upload = App::htmls()->createFileUpload($this->urlCreator->createUrl('upload'), false, '');
        $upload->setDeleteUrl($this->urlCreator->createUrl('delete-upload'));
        $upload->setPreviewTemplate(App::htmls()->createUploadPreview('my_upload', null, '', false, true));
        $allowedExtensions = App::getFileTools()->getExtensionsByFileType(FileTypeConst::IMAGE);
        $upload->setAllowedExtensions($allowedExtensions);
        $upload->setAccept('image/*');
        
        $libraryDefinition = AssociationDefinition::getInstance();
        $library = $this->input->getDAO($libraryDefinition)->getById(13);
        if($library !== null){
            $fileValue = $library->getId();
            $fileName = $library->getImage();
            if(!empty($fileName)){
                $fileUrl = App::getApp()->getModelFileUrl($libraryDefinition, $library, 'image');
                $fileLocation = App::getApp()->getModelFullFile($libraryDefinition, $library, 'image');
                $preview = App::getApp()->getFilePreview($this->input->getSubAppName(), $fileUrl, $fileLocation, $fileName, false, null);
                $preview = App::htmls()->createUploadPreview('saved_'.'my_upload', $fileValue, $preview, true);
                $upload->addPreview($preview);
            }
        }
        $this->result->setContent($upload);
    }
    
    protected function processInputIcon()
    {
        $input = App::htmls()->createHtmlInput('text', 'my_input', null, 'Search...');
        $icon = App::htmls()->createClassIcon('la la-search');
        $content = App::htmls()->createInputIcon($input, $icon, IconPosition::RIGHT);
        $this->result->setContent($content);
    }
    
    protected function processInputGroup()
    {
        $input = App::htmls()->createHtmlInput('text', 'my_input', null, 'Units');
        $child1 = App::htmls()->createInputGroupText(App::createHtmlString('#'));
        $child2 = App::htmls()->createInputGroupText(App::createHtmlString('px'));
        $content = App::htmls()->createInputGroup($input);
        $content->addLeftChild($child1);
        $content->addRightChild($child2);
        $this->result->setContent($content);
    }
    
    protected function processInputGroupIcon()
    {
        $input = App::htmls()->createHtmlInput('text', 'my_input', null, 'Login');
        $child = App::htmls()->createInputGroupText(App::createFAIcon('user'));
        $content = App::htmls()->createInputGroup($input);
        $content->addRightChild($child);
        $this->result->setContent($content);
    }
    
    protected function processInputGroupButton()
    {
        $input = App::htmls()->createHtmlInput('text', 'my_input', null, 'Search...');
        $child = App::htmls()->createButton(App::createHtmlString('Go'));
        $content = App::htmls()->createInputGroup($input);
        $content->addRightChild($child);
        $this->result->setContent($content);
    }
    
    protected function processCustomInputGroup()
    {
        $input1 = App::htmls()->createHtmlInput('text', 'from', null, 'From');
        $input2 = App::htmls()->createHtmlInput('text', 'to', null, 'To');
        $content = App::htmls()->createCustomInputGroup(array($input1, $input2));
        $this->result->setContent($content);
    }
    
    protected function processDatePicker()
    {
        $input = App::htmls()->createHtmlInput('text', 'date', null, 'Select a date');
        $langIso = $this->input->getLanguageInfo()->getLanguage();
        $input->convertToDatePicker($langIso);
        $this->result->setContent($input);
    }
    
    protected function processDatePickerInputGroup()
    {
        $input = App::htmls()->createHtmlInput('text', 'date', null, 'Select a date');
        $icon = App::htmls()->createClassIcon('la la-calendar-check-o');
        $child = App::htmls()->createInputGroupText($icon);
        $content = App::htmls()->createInputGroup($input);
        $content->addRightChild($child);
        $langIso = $this->input->getLanguageInfo()->getLanguage();
        $input->convertToDatePicker($langIso);
        $this->result->setContent($content);
    }
    
    protected function processDatetimePicker()
    {
        $input = App::htmls()->createHtmlInput('text', 'date', null, 'Select a date');
        $langIso = $this->input->getLanguageInfo()->getLanguage();
        $input->convertToDateTimePicker($langIso);
        $this->result->setContent($input);
    }
    
    protected function processDateRange()
    {
        $input1 = App::htmls()->createHtmlInput('text', 'from', null, 'From');
        $input2 = App::htmls()->createHtmlInput('text', 'to', null, 'To');
        $content = App::htmls()->createCustomInputGroup(array($input1, $input2));
        $langIso = $this->input->getLanguageInfo()->getLanguage();
        $content->convertToRangeDatePicker($langIso);
        $this->result->setContent($content);
    }
    protected function processForm()
    {
        $form = App::htmls()->createForm('my_template');
        $nomInput = App::htmls()->createHtmlInput('text', 'my_input',$this->l('Association'));
        $nomField = App::htmls()->createFormField('nom', null,  $nomInput);
        $form->addChild($nomField);
        
       //ajout du siege

        $siegeInput = App::htmls()->createHtmlInput('text', '',$this->l('Siege'));
        $siegeField = App::htmls()->createFormField('siege', null,  $siegeInput);
        $form->addChild($siegeField);
        $this->result->setContent($form);
        //ajout de la devise
        $deviseInput = App::htmls()->createHtmlInput('text', '',$this->l('Devise'));
        $siegeField = App::htmls()->createFormField('Devise', null,  $deviseInput);
        $form->addChild($siegeField);
        $this->result->setContent($form);
         //ajout du contact
         $telephoneInput = App::htmls()->createHtmlInput('text', '',$this->l('Telephone'));
        $telephoneField = App::htmls()->createFormField('telephone', null,  $telephoneInput);
        $form->addChild($telephoneField);
       
        //nom de l'amin
        $adminInput = App::htmls()->createHtmlInput('text', '',$this->l('Administrateur(nom)'));
        $adminField = App::htmls()->createFormField('admin', null,  $adminInput);
        $form->addChild($adminField);
        $this->result->setContent($form);
        //prenom de l'amin
        $admin_prenomInput = App::htmls()->createHtmlInput('text', '',$this->l('Administrateur(prenom)'));
        $adminField = App::htmls()->createFormField('padmin', null,  $admin_prenomInput);
        $form->addChild($adminField);
        $this->result->setContent($form);
        //mot de passe
        $this->result->setContent($form);
        $passwordInput = App::htmls()->createHtmlInput('password', 'password', null, $this->l('Password'));
            $passwordField = App::htmls()->createFormField('password', null, $passwordInput);
            $form->addChild($passwordField);

            //mot de passe
        $this->result->setContent($form);
        $passwordInput = App::htmls()->createHtmlInput('password', 'password', null, $this->l('confirmer votre Password'));
            $passwordField = App::htmls()->createFormField('password', null, $passwordInput);
            $form->addChild($passwordField);
            //
            $admin_prenomInput = App::htmls()->createHtmlInput('text', '',$this->l('Email'));
            $adminField = App::htmls()->createFormField('email', null,  $admin_prenomInput);
            $form->addChild($adminField);
            $this->result->setContent($form);
            //
            $admin_prenomInput = App::htmls()->createHtmlInput('text', '',$this->l('Status'));
            $adminField = App::htmls()->createFormField('status', null,  $admin_prenomInput);
            $form->addChild($adminField);
    }
}
