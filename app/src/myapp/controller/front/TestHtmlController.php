<?php
namespace myapp\controller\front;

use muuska\controller\AbstractController;
use muuska\html\constants\ButtonStyle;
use muuska\html\constants\IconPosition;
use muuska\html\form;
use muuska\html\HtmlContent;
use muuska\util\App;
use muuska\dao\util\SelectionConfig;
use myapp\option\AccessibilityProvider;
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
use \muuska\dao\AbstractDAO;
use myapp\model\Epargne;

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
    
    protected function processTable()
    {
        $definition = AssociationDefinition::getInstance();
        $data = $this->input->getDAO($definition)->getData($this->input->createSelectionConfig());
        $table = App::htmls()->createTable($data );
        $nameRenderer = App ::renderers()->createSimpleValueRenderer(App::getters()->createModelValueGetter($definition, 'nom_assoc'));
        $table->createField('nom_assoc', $nameRenderer,  'Nom');
        $accesRenderer = App ::renderers()->createOptionLabelRenderer(new AccessibilityProvider($this->input->getLang()), 
        App::getters()->createModelValueGetter($definition, 'accessibility'));
        $table->createField('accessible', $accesRenderer ,  'Accessible');
        $table->addClassesFromString('table-striped table-bordered table-hover');
        $this->result->setContent($table);
     
    }
    protected function processFullNav()
    {
        $nav = App::htmls()->createHtmlNav();
        $imageItem = $nav->createItem('image', App::createHtmlString('Image'));
        /*Contenu de l'element*/
        $imageItem->setNavContent(App::htmls()->createHtmlImage('http://localhost/test/img.jpg', 'My image'));
        /*Indiquer que l'element a été chargé*/
        $imageItem->setLoaded(true);
        /*Element actif*/
        $imageItem->setActive(true);
        $buttonItem = $nav->createItem('button', App::createHtmlString('Button'));
        $buttonItem->setNavContent(App::htmls()->createButton(App::createHtmlString('My button')));
        $buttonItem->setLoaded(true);
        $nav->createItem('form', App::createHtmlString('association'), $this->urlCreator->createUrl(''));
        $nav->createItem('menu', App::createHtmlString('Menu'), $this->urlCreator->createUrl('menu'));
        $nav->createItem('table', App::createHtmlString('Table'), $this->urlCreator->createUrl('table'));
        $nav->createItem('tree', App::createHtmlString('Tree'), $this->urlCreator->createUrl('tree'));
        $fullNav = App::htmls()->createFullNavigation($nav);
        $this->result->setContent($fullNav);
    }
    /**
     * @param \muuska\getter\Getter $subValuesGetter
     */

    protected function processMenuArray()
    {
                    $data = array(
                    array(
                        'title' => 'Test html',
                        'children' => array(
                                            array(
                                            'title' => 'Image',
                                            'controller' => 'test-html',
                                            'action' => 'image'
                                        ),
                        array(
                            'title' => 'Commandes',
                            'children' => array(
                                array(
                                'title' => 'Button',
                                'controller' => 'test-html',
                                'action' => 'button'
                                ),
                                array(
                                'title' => 'Link',
                                'controller' => 'test-html',
                                'action' => 'link'
                                ),
                    )
                    ),
                        array(
                            'title' => 'Form',
                            'controller' => 'test-html',
                            'action' => 'form'
                        ),
                        array(
                        'title' => 'Menu array',
                        'controller' => 'test-html',
                        'action' => 'menu-array'
                        ),
                    )
                    ),
                    array(
                    'title' => 'Test model',
                    'controller' => 'test-model'
                    ),
                    array(
                    'title' => 'Test DAO',
                    'controller' => 'test-dao'
                    )
                    );
                    $menu = App::htmls()->createMenuList($data);
                    $menu->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createArrayValueGetter('title')));
                    $menu->setSubValuesGetter(App::getters()->createArrayChildrenGetter());
                    $menu->createDefaultAction(App::urls()->createArrayUrl($this->input));
                    $this->result->setContent($menu);
            
          }
                    protected function processMenu()
                    {
                    $definition = AssociationDefinition::getInstance();
                    $selectionConfig = $this->input->createSelectionConfig();
                    
                    $dao = $this->input->getDAO($definition);
                    $data = $dao->getData($selectionConfig);
                   
                    $menu = App::htmls()->createHtmlTree($data);
                    $menu->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createModelValueGetter($definition, 'nom_assoc')));
                    
                    $menu->setSubValuesGetter(App::getters()->createChildrenModelGetter($dao, $this->input->createSelectionConfig()));
                    $menu->createDefaultAction(App::urls()->createModelUrl($this->input, $definition, 'view'));
                    $this->result->setContent($menu);
                    }
                    protected function processNav()
                        {
                            $nav = App::htmls()->createHtmlNav();
                            $nav->createItem('home', App::createHtmlString('Home'), '#',
                            App::createFAIcon('home'));
                            $nav->createItem('user', App::createHtmlString('Users'), 'membre',
                            App::createFAIcon('user'));
                            $nav->createItem('association', App::createHtmlString('Association'));
                            $productItem = $nav->createItem('product', App::createHtmlString('Products'));
                            /*Element actif*/
                            $productItem->setActive(true);
                            $this->result->setContent($nav);
                        }
     protected function processMenuTemplate()
   {
        $definition = AssociationDefinition::getInstance();
        $selectionConfig = $this->input->createSelectionConfig();

        $dao = $this->input->getDAO($definition);
        $data = $dao->getData($selectionConfig);
        $menu = App::htmls()->createMenuList($data);
        $menu->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createModelValueGetter($definition,'nom_assoc')));
          $menu->createDefaultAction(App::urls()->createModelUrl($this->input,$definition, 'view'));
   /*Template*/
          $menu->setRenderer($this->getThemeTemplate('listing/tree/menu/simple/list'));
          $menu->setItemRenderer($this->getThemeTemplate('listing/tree/menu/simple/item'));
            $this->result->setContent($menu);
   }


    protected function processTree()
   {
       $definition = profilDefinition::getInstance();
       $selectionConfig = $this->input->createSelectionConfig();
       $selectionConfig->addRestrictionFieldFromParams('parentId', null);
       $dao = $this->input->getDAO($definition);
       $data = $dao->getData($selectionConfig);
       $tree = App::htmls()->createHtmlTree($data);
       $tree->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createModelValueGetter($definition,'type_profil')));
       $tree->setSubValuesGetter(App::getters()->createChildrenModelGetter($dao,$this->input->createSelectionConfig()));
       $this->result->setContent($tree);
   }


   protected function processTableau()
   {
    $lib = MembreDefinition::getInstance();
    $dat = $this->input->getDAO($lib)->getData($this->input->createSelectionConfig());
    $tabl = App::htmls()->createTable($dat );
    $namRenderer = App ::renderers()->createSimpleValueRenderer(App::getters()->createModelValueGetter($lib , 'nom'));
    $tabl->createField('nom', $namRenderer,  'Nom');
    $definition = EpargneDefinition::getInstance();
    $data = $this->input->getDAO($definition)->getData($this->input->createSelectionConfig());
    $table = App::htmls()->createTable($data );
    $nameRenderer = App ::renderers()->createSimpleValueRenderer(App::getters()->createModelValueGetter($definition, 'montant_epargne'));
    $table->createField('montant_epargne', $nameRenderer,  'Epargne');
    $typeRenderer = App ::renderers()->createSimpleValueRenderer(App::getters()->createModelValueGetter($definition, 'type_epargne'));
    $table->createField('type_epargne', $typeRenderer ,  'type_epargne');
    $table->addClassesFromString('table-striped table-bordered table-hover');
    $this->result->setContent($table);
    
   }
   protected function processTableauEpargne()
   {
    $table = App::htmls()->createTable();
    $nameRenderer = App ::renderers()->createSimpleValueRenderer(App::getters()->createModelValueGetter($definition, 'nom_assoc'));
   }
   protected function processSelectAssociation() {
    $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
    $selectionConfig = $this->input->createSelectionConfig();
    $selectionConfig->setSelectionAssociationParams('membreId') ;
    $data = $libraryDao->getData($selectionConfig);
    $data = App::htmls()->createTable($data );
    foreach ($data as $object) {   
    var_dump($object->getAssociated('membreId'));
    }
    }
    protected function processSort() {
        $libraryDao = $this->input->getDAO(AssociationDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setSortOptionParams('nom_assoc', SortDirection::DESC);
        $data = $libraryDao->getData($selectionConfig);
        var_dump($data);
        foreach ($data as $model){
            print_r($model->getNom_assoc());
            echo '<br>';
        }
    }
    protected function processSortAssociation() {
        $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $sortOption = App::daos()->createSortOption('membreId', SortDirection::ASC);
        $sortOption->setForeign(true);
        $sortOption->setExternalField('nom');
        $selectionConfig->addSortOption($sortOption, 'nom');
        $selectionConfig->setSelectionAssociationParams('membreId');
        $data = $libraryDao->getData($selectionConfig);
        $table = App::htmls()->createTable($data );
        var_dump( $data = $libraryDao->getData($selectionConfig));
        foreach ($data as $model){
            $address = $model->getAssociated('membreId');
            print_r($address->getPropertyValue('nom') . ' : ' . $model->getnom());
            echo '<br>';
        }
    }
    
    public function getInteret($taux_epargne,$montant_epargne){
        $interet_epargne =$montant_epargne *= 1 + $taux_epargne;
        return $interet_epargne;
       
    }
    protected function processSelectMembre()
    {
        $membre = new AccessibilityProvider($this->input->getLang());
        $select = App::htmls()->createSelect('my_select',$membre, null, true);
        $this->result->setContent($select);
    }
    protected function processField()
   { $epar=0;
        $form = App::htmls()->createForm($this->urlCreator->createDefaultUrl());
        $field = App::htmls()->createFormField('field1', App::createHtmlLabel('Field'), App::htmls()->createHtmlInput('text', 'field'));
        $field->setRequired(true);
        $field->setHelpText('My help text');
        $field->setError('Invalid value');
        $form->addChild($field);
        $submit = App::htmls()->createButton(App::createHtmlString('Submit'), 'submit', null, ButtonStyle::PRIMARY);
        $cancel = App::htmls()->createHtmlLink(App::createHtmlString('Cancel'), '#',null, '', true);
        $form->setSubmit($submit);
        $form->setCancel($cancel);
        $form->setLabel('My form');
        $this->result->setContent($form);
    }
}