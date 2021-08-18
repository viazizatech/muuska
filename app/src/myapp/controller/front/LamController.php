<?php
namespace myapp\controller\front;

use muuska\util\App;
use myapp\model\ProductDefinition;
use muuska\asset\constants\AssetType;
use muuska\html\constants\ButtonStyle;
use muuska\dao\constants\SortDirection;
use muuska\controller\AbstractController;
use muuska\http\constants\RedirectionType;


class LamController extends AbstractController
{
    protected function processDefault()

{

    $content = App::htmls()->createHtmlCustomElement();
    $areaCreator = App::htmls()->createDefaultAreaCreator();
        $nav = App::htmls()->createHtmlNav();
        $nav->createItem('home', App::createHtmlString('Home'), 'home',
        App::createFAIcon('home'));
        $nav->createItem('user', App::createHtmlString('Users'), 'customer',
        App::createFAIcon('user'));
        $nav->createItem('category', App::createHtmlString('Categories'));
        $productItem = $nav->createItem('product', App::createHtmlString('Products'));
        /*Element actif*/
        $productItem->setActive(true);
        $this->result->setContent($nav);
        $stayConnected=0;
        $rememberMe = App::htmls()->createCheckbox('stay_connected', $this->l('Remember me'), 1, $stayConnected);
            $areaCreator = App::htmls()->createDefaultAreaCreator();
            $areaCreator->addContentCreator($rememberMe, 'extraLeft');
       // $content->setRenderer($this->input->getsubProject()->createTemplate('lam'));
      // $this->result->setContent($content);
      // $areaCreator = App::htmls()->createDefaultAreaCreator();

/*Banner*/
$bannerTitle = $this->l('Is there professional in your competence');
$bannerSubTitle = $this->l('Everyone can become pro');
$mainLink = App::htmls()->createHtmlLink(App::createHtmlString($this->l('Get started')), '#', null, $this->l('Click here to start'), true,
ButtonStyle::PRIMARY);
$image = $this->input->getSubProject()->createHtmlImage('banner.jpg');
$banner = App::htmls()->createBanner($image, $bannerTitle,
$bannerSubTitle, $mainLink);
//$areaCreator->addHtmlComponent($banner, 'banner');
$areaCreator->addContentCreator($banner, 'banner');



}

    /**
     * {@inheritDoc}
     * @see \muuska\controller\AbstractController::formatHtmlPage()
     */
  /*  protected function formatHtmlPage(\muuska\controller\event\ControllerPageFormatingEvent $event, $fireFormattingEvent = true)
    {
        $this->input->getTheme()->createHtmlImage('fsfsfsf');
        $title = App::createHtmlString($this->l('Sign In To Admin'), 'loginTitle');
        //$title = App::createHtmlImage('logo.png', 'Logo', 'Logo');
        $event->addContentCreator($title);
        $this->addThemeAsset(AssetType::CSS, 'bootstrap.min.css');
        $this->addThemeAsset(AssetType::CSS, 'style.css');
        $this->addThemeAsset(AssetType::CSS, 'style.css');
        $this->addThemeAsset(AssetType::CSS, 'aos.css');
        $this->addThemeAsset(AssetType::CSS, 'bootstrap-icons.css');
        $this->addThemeAsset(AssetType::CSS, 'boxicons.min.css');
        $this->addThemeAsset(AssetType::CSS, 'remixicon.css');
        $this->addThemeAsset(AssetType::CSS, 'glightbox.min.css');
        $this->addThemeAsset(AssetType::CSS, 'swiper-bundle.min.css');
        $this->addThemeAsset(AssetType::JS, 'aos.js');
        $this->addThemeAsset(AssetType::JS, 'swiper-bundle.min.js');
        $this->addThemeAsset(AssetType::JS, 'purecounter.js');
        $this->addThemeAsset(AssetType::JS, 'validate.js');
        $this->addThemeAsset(AssetType::JS, 'isotope.pkgd.min.js');
        $this->addThemeAsset(AssetType::JS, 'glightbox.min.js');
        $this->addThemeAsset(AssetType::JS, 'bootstrap.bundle.min.js');
       // $this->addThemeAsset(AssetType::JS, 'main.js');
        parent::formatHtmlPage($event, false);
    }
    
    
    
    protected function getBackUrl() {
        ;
    }*/

   

}