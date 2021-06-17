<?php
namespace myapp\controller\front;

use muuska\constants\FolderPath;
use muuska\controller\AbstractController;
use muuska\util\App;
use myapp\option\AccessibilityProvider;
use myapp\model\LibraryDefinition;
use muuska\html\HtmlContent;
use muuska\html\constants\ButtonStyle;
use muuska\html\constants\IconPosition;
use muuska\html\form;

use muuska\dao\util\SelectionConfig;
use muuska\constants\FileTypeConst;
use myapp\model\AssociationDefinition;
use myapp\model\MembreDefinition;
use muuska\asset\constants\AssetType;
use muuska\html\listing\item\ListItem;
use \muuska\html\listing\tree\MenuList;
use \muuska\html\listing\tree\HtmlTree;
use muuska\html\listing\item\ListItemContainer;
use myapp\model\EpargneDefinition;
use myapp\model\ ProfilDefinition;
use myapp\model\ SeanceDefinition;
use muuska\html\listing\AbstractList;
use muuska\dao\constants\DAOFunctionCode;
use muuska\dao\constants\SortDirection;
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
        $areaCreator = App::htmls()->createDefaultAreaCreator();
/*Banner*/
$bannerTitle = $this->l('Is there professional in your competence');
$bannerSubTitle = $this->l('Everyone can become pro');
$mainLink = App::htmls()->createHtmlLink(App::createHtmlString($this->l('Get started')), '#', null, $this->l('Click here to start'), true,ButtonStyle::PRIMARY);
$image = $this->input->getSubProject()->createHtmlImage('banner.jpg');
$banner = App::htmls()->createBanner($image, $bannerTitle,
$bannerSubTitle, $mainLink);
$areaCreator->addHtmlComponent($banner, 'banner');
/*Picto*/
$pictorData = array(
array(
'icon' => 'fa fa-chalkboard-teacher',
'title' => $this->l('Training'),
'subTitle' => $this->l('You will be trained.'),
),
array(
'icon' => 'fa fa-graduation-cap',
'title' => $this->l('Certification'),
'subTitle' => $this->l('You will be certified.'),
),
array(
'icon' => 'fa fa-handshake',
'title' => $this->l('Work'),
'subTitle' => $this->l('You will have work'),
),
);
$picto = App::htmls()->createPresentationList($pictorData);
$picto->addClass('picto');
$picto->createIconField(App::renderers()->createClassIconValueRenderer(App::getters()->createArrayValueGetter('icon')));
$picto->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createArrayValueGetter('title')));
$picto->createSubTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createArrayValueGetter('subTitle')));
$areaCreator->addHtmlComponent($picto, 'picto');
/*categories*/

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
