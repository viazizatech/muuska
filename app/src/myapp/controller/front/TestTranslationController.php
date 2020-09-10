<?php
namespace myapp\controller\front;

use muuska\constants\FolderPath;
use muuska\controller\AbstractController;
use muuska\util\App;
use myapp\option\AccessibilityProvider;
use myapp\model\LibraryDefinition;

class TestTranslationController extends AbstractController
{
    protected function processDefault()
    {
        $filePattern = App::getApp()->getStorageDir().FolderPath::TRANSLATION.'/{lang}/main';
        $loader = App::translations()->createJSONTranslationLoader($filePattern);
        $translator = App::translations()->createDefaultTranslator($loader);
        var_dump($translator->translate($this->input->getLang(), 'Hello world', 'everyone'));
    }
    
    protected function processMultiple()
    {
        $filePattern = App::getApp()->getStorageDir().FolderPath::TRANSLATION.'/{lang}/multiple';
        $loader = App::translations()->createJSONTranslationLoader($filePattern);
        $multipleLoader = App::translations()->createDefaultMultipleLoader($loader);
        
        $helloWorldLoader = $multipleLoader->getLoader('hello-world');
        $helloWorldTranslator = App::translations()->createDefaultTranslator($helloWorldLoader);
        var_dump($helloWorldTranslator->translate($this->input->getLang(), 'Hello world', 'everyone'));
        
        $goodbyeLoader = $multipleLoader->getLoader('goodbye');
        $goodbyeTranslator = App::translations()->createDefaultTranslator($goodbyeLoader);
        var_dump($goodbyeTranslator->translate($this->input->getLang(), 'Good bye'));
    }
    
    protected function processOption()
    {
        $accessibilityProvider = new AccessibilityProvider($this->input->getLang());
        var_dump($accessibilityProvider->getOptions());
    }
    
    protected function processModel()
    {
        $config = App::translations()->createModelTranslationConfig(LibraryDefinition::getInstance());
        $translator = $this->input->getProject()->getTranslator($config);
        var_dump($translator->translate($this->input->getLang(), 'library', 'title'));
        var_dump($translator->translate($this->input->getLang(), 'addressId'));
        var_dump($translator->translate($this->input->getLang(), 'name'));
        var_dump($translator->translate($this->input->getLang(), 'accessibility'));
        var_dump($translator->translate($this->input->getLang(), 'openingTime'));
        var_dump($translator->translate($this->input->getLang(), 'image'));
        var_dump($translator->translate($this->input->getLang(), 'description'));
    }
    
    protected function processTemplates()
    {
        $template = $this->input->getProject()->createTemplate('my_template');
        $this->result->setContent(App::htmls()->createHtmlCustomElement(null, $template));
    }
    
    protected function processCustom()
    {
        $config = App::translations()->createCustomTranslationConfig('first_custom');
        $translator = $this->input->getProject()->getTranslator($config);
        var_dump($translator->translate($this->input->getLang(), 'My custom text'));
    }
    
    protected function processExtra()
    {
        $config = App::translations()->createDefaultTranslationConfig('extra', 'extra1');
        $translator = $this->input->getProject()->getTranslator($config);
        var_dump($translator->translate($this->input->getLang(), 'My extra text'));
    }
}
