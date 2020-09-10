<?php
namespace muuska\util\theme;

use muuska\asset\constants\AssetNames;
use muuska\project\constants\SubAppName;
use muuska\util\App;

class DefaultTheme extends AbstractTheme
{
    protected function init(){
        parent::init();
        $this->addAvailableComponent('page', 'page/default'); 
        $this->addAvailableComponent('alert', 'alert/default'); 
        $this->addAvailableComponent('custom_dropdown', 'dropdown/custom'); 
        $this->addAvailableComponent('default_dropdown', 'dropdown/default'); 
        $this->addAvailableComponent('split_dropdown', 'dropdown/split'); 
        $this->addAvailableComponent('form_field', 'form/field');
        $this->addAvailableComponent('form', 'form/form');
        $this->addAvailableComponent('grid_form_field', 'form/grid_field');
        $this->addAvailableComponent('grid_translatable_field', 'form/grid_translatable_field');
        $this->addAvailableComponent('quick_search_form', 'form/quick_search_form');
        $this->addAvailableComponent('translatable_field', 'form/translatable_field');
        $this->addAvailableComponent('default_input', 'input/default_input');
        $this->addAvailableComponent('input_icon', 'input/input_icon');
        $this->addAvailableComponent('radio', 'input/radio');
        $this->addAvailableComponent('radio_switch', 'input/radio_switch');
        $this->addAvailableComponent('select', 'input/select');
        $this->addAvailableComponent('select2', 'input/select2');
        $this->addAvailableComponent('textarea', 'input/textarea');
        $this->addAvailableComponent('rich_text_editor', 'input/rich_text_editor');
        $this->addAvailableComponent('checkbox', 'input/checkbox');
        $this->addAvailableComponent('file_upload', 'input/file_upload');
        $this->addAvailableComponent('upload_preview', 'input/upload_preview');
        $this->addAvailableComponent('pagination', 'listing/pagination/default');
        $this->addAvailableComponent('acccordion', 'listing/acccordion/list');
        $this->addAvailableComponent('table', 'listing/table/table');
        $this->addAvailableComponent('table_item', 'listing/table/row');
        $this->addAvailableComponent('menu_list', 'listing/tree/menu/simple/list');
        $this->addAvailableComponent('menu_list_item', 'listing/tree/menu/simple/item');
        $this->addAvailableComponent('tree', 'listing/tree/custom/list');
        $this->addAvailableComponent('tree_item', 'listing/tree/custom/item');
        $this->addAvailableComponent('limiter_switcher', 'listing/limiter_switcher');
        $this->addAvailableComponent('full_navigation', 'nav/full_navigation');
        $this->addAvailableComponent('nav_item', 'nav/nav_item');
        $this->addAvailableComponent('nav', 'nav/nav');
        $this->addAvailableComponent('children_container', 'children_container');
        $this->addAvailableComponent('field_value', 'field_value');
        $this->addAvailableComponent('grid_field_value', 'grid_field_value');
        $this->addAvailableComponent('panel', 'panel/main');
        $this->addAvailableComponent('list_panel', 'panel/list/main');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\AbstractTheme::createAssetGroup()
     */
    public function createAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        $result = parent::createAssetGroup($name, $assetSetter);
        if($result === null){
            if($name === AssetNames::RICH_TEXT_EDITOR_GROUP){
                $result = App::assets()->createAssetGroup($name, array($this->createJS('tinymce.bundle.js', 'tinymce')));
            }elseif($name === AssetNames::JQUERY_UI_GROUP){
                $result = App::assets()->createAssetGroup($name, array(
                    $this->createJS('jquery-ui.bundle.js', 'jquery-ui'),
                    $this->createCSS('jquery-ui.bundle.css', 'jquery-ui')
                ));
            }
        }
        if(($result !== null) && !$assetSetter->hasAssetGroup($name)){
            $assetSetter->addAssetGroup($result);
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\AbstractTheme::createDefaultAssetGroup()
     */
    public function createDefaultAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        $jsOption = array(
            'colors' => array(
                'state' => array('brand' => '#5d78ff', 'dark' => '#282a3c', 'light' => '#ffffff', 'primary' => '#5867dd', 'success' => '#34bfa3', 'info' => '#36a3f7', 'warning' => '#ffb822', 'danger' => '#fd3995'),
                'colors' => array(
                    'label' => array('#c5cbe3', '#a1a8c3', '#3d4465', '#3e4466'),
                    'shape' => array('#f0f3ff', '#d9dffa', '#afb4d4', '#646c9a'),
                )
            )
        );
        return App::assets()->createAssetGroup($name, array(
            $this->createAbsoluteCSS('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all'),
            $this->createCSS('plugins.bundle.css'),
            $this->createCSS('style.bundle.css'),
            $this->createCSS('custom.css'),
            App::assets()->createJsVariable('KTAppOptions', $jsOption, false),
            $this->createJS('plugins.bundle.js'),
            $this->createJS('scripts.bundle.js')
        ));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\AbstractTheme::createMailDefaultAssetGroup()
     */
    public function createMailDefaultAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\AbstractTheme::createSubAppAssetGroup()
     */
    public function createSubAppAssetGroup($name, $subAppName, \muuska\asset\AssetSetter $assetSetter){
        $result = null;
        if($subAppName === SubAppName::BACK_OFFICE){
            $result = App::assets()->createAssetGroup($name, array(
                $this->createCSS('skins/header/base/light.css'),
                $this->createCSS('skins/header/menu/light.css'),
                $this->createCSS('skins/brand/dark.css'),
                $this->createCSS('skins/aside/dark.css'),
            ));
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\AbstractTheme::getFilePreview()
     */
    public function getFilePreview($fileUrl, $fileLocation, $shortFileName, $useOriginalFileDetails = false, \muuska\util\upload\UploadInfo $uploadInfo = null){
        return '<i class="fa fa-file"></i>';
    }
}
