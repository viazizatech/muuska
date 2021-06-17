<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\EpargneDefinition;
use myapp\model\EmpruntDefinition;
use muuska\controller\AbstractController;
use muuska\util\App;
use \muuska\dao\AbstractDAO;
use muuska\constants\operator\LogicalOperator;
use muuska\constants\operator\Operator;
use muuska\dao\constants\DAOFunctionCode;
use muuska\dao\constants\SortDirection;
class CaisseAdminController extends AbstractController
{
    protected function processDefault()
    {
        
        $listPanel = App::htmls()->createListPanel($this->l('Caisse d \' epargne'));
       
        $epargneDao = $this->input->getDAO(EpargneDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setSelectionAssociationParams('membreId');
        $dat =  $epargneDao->getData($selectionConfig);
        $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $sortOption = App::daos()->createSortOption('membreId', SortDirection::ASC);
        $sortOption->setForeign(true);
        $sortOption->setExternalField('id');
        $selectionConfig->addSortOption($sortOption, 'id');
        $selectionConfig->setSelectionAssociationParams('membreId');
        $data = $libraryDao->getData($selectionConfig);
        /*$membre= array();
        $membre['nom']='';
        $membre['prenom']='';
        $membre['totalmembre']=0;
        $membre['interet']=0;
        $membre['global']=0;
        foreach ($data as  $model){
            $address = $model->getAssociated('membreId');
            //print_r($address->getPropertyValue('nom') . ' : ' . $model->getName());
            $membre['nom']=$address->getPropertyValue('nom');
            $membre['prenom']=$address->getPropertyValue('prenom');
            $membre['totalmembre']  = (float)$model->getPropertyValue('montant_epargne'); 
            $membre['interet'] = (float)$model->getPropertyValue('taux_epargne');
            $membre['global'] = (float)$model->getPropertyValue('taux_epargne');
              /* foreach ($data as $epargne){  
                $membre['totalmembre']  = (float)$epargne->getPropertyValue('montant_epargne'); 
               
                }
            
                 
                print_r($membre );  
        }
        return $membre ;*/
        
        $table = App::htmls()->createTable();
        $nameRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data;
        }));
        $table->createField('name', $nameRenderer, $this->l('Name'));
        $displayNameRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data->getNom($this->input->getLang());
        }));
        $table->createField('Seance', $displayNameRenderer, $this->l('nom de la seance'  ));  
        $descriptionRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data->getDescription($this->input->getLang());
        }));
        $total_membreRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data->getDescription($this->input->getLang());
        }));
        $table->createField('description', $total_membreRenderer, $this->l('T.membre'));
        $interetRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data->getDescription($this->input->getLang());
        }));
        $table->createField('interets', $interetRenderer, $this->l('Interets'));
        $interetRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data->getDescription($this->input->getLang());
        }));
        $table->createField('global', $descriptionRenderer, $this->l('global'));
        $globalRenderer = App::renderers()->createSimpleValueRenderer(App::getters()->createDefaultGetter(function ($data) {
            return $data->getDescription($this->input->getLang());
        }));
        $table->createField('global', $globalRenderer, $this->l('Global'));
        
        $listPanel->setInnerContent($table);
        $this->result->setContent($listPanel);
        /*$template = $this->input->getProject()->createTemplate('caisse');
        $this->result->setContent(App::htmls()->createHtmlCustomElement(null, $template)); */      
    }
   

        protected function processEpargneTotal() {
            $content = App::htmls()->createHtmlCustomElement();
$content->setRenderer($this->input->getSubProject()->createTemplate('caisse'));
$this->result->setContent($content);
           
        }  

        protected function processSort() {
            $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
            $selectionConfig = $this->input->createSelectionConfig();
            $selectionConfig->setSortOptionParams('montant_epargne', SortDirection::DESC);
            $data = $libraryDao->getData($selectionConfig);
            $montant_epargne = 0;
            foreach ($data as $model){
                $montant_epargne=(float)$model->getPropertyValue('montant_epargne');
                echo '<br>';
                print_r($montant_epargne);
            }
           
        }
        protected function processSelect() {
            $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
            $selectionConfig = $this->input->createSelectionConfig();
            $selectionConfig->setSelectionAssociationParams('membreId');
            $data = $libraryDao->getData($selectionConfig);
            $lib='';
            foreach ($data as $object) {
               $lib= ($object->getPropertyValue('membreId'));
                print_r($lib);
            }
        }
        protected function processSortAssociation() {
            $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
            $selectionConfig = $this->input->createSelectionConfig();
            $sortOption = App::daos()->createSortOption('membreId', SortDirection::ASC);
            $sortOption->setForeign(true);
            $sortOption->setExternalField('id');
            $selectionConfig->addSortOption($sortOption, 'id');
            $selectionConfig->setSelectionAssociationParams('membreId');
            $data = $libraryDao->getData($selectionConfig);
            $membre= array();
            $membre['nom']='';
            $membre['prenom']='';
            $totalmembre=0;
            $membre['interet']=0;
            $membre['global']=0;
            foreach ($data as  $model){
                $address = $model->getAssociated('membreId');
                //print_r($address->getPropertyValue('nom') . ' : ' . $model->getName());

                $membre['nom']=$address->getPropertyValue('nom');
                $membre['prenom']=$address->getPropertyValue('prenom');
                $membre['totalmembre']  = (float)$model->getPropertyValue('montant_epargne'); 
                $membre['interet'] = (float)$model->getPropertyValue('taux_epargne');
                $membre['global'] = (float)$model->getPropertyValue('taux_epargne');
                
                   foreach ($data  as $epargne){  
                    $totalmembre += (float)$epargne->getPropertyValue('montant_epargne'); 
                   
                        
                    print_r($membre );  
                    }
                
                     
                    print_r($membre );  
            }
            return $membre ;
        }
}
