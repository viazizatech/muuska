<?php
namespace myapp\controller\admin;
use muuska\util\App;
use  myapp\controller\admin\partial\CrudAdminController;
use myapp\model\EmpruntDefinition;
use muuska\constants\ExternalFieldEditionType;
use muuska\constants\ExternalFieldViewType;
use muuska\html\form\FormField;
use myapp\option\AccessibilityProvider;
use muuska\html\constants\ActionOpenMode;
use muuska\html\constants\AlertType;
use muuska\http\constants\RedirectionType;
use myapp\model\Emprunt;


class EmpruntAdminController extends CrudAdminController 
{
    
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = EmpruntDefinition::getInstance();
    }
    // methode pour recuperer verifier l'emprunt
   protected function processWithdraw(){
        $dao = $this->input->getDAO(EmpruntDefinition::getInstance());
        $data = $dao->getData();
        $epar = parent::TotalEpargne();
        $emprunt = new Emprunt();
        var_dump( $emprunt->getVerifEmprunt($epar));
       /* $min= 400000;
        $verifemprunt=0;
        if($min < $epar ){
          return  $this->result->addError($this->l('la somme n\' est pas diponible'));
        }else if($min > $epar) {
            return  $this->result->addSuccess($this->l('ok'));
        }*/
        
            
    } 
    
    

    protected function createFormHelper($update)
    {   
        $helper = parent::createFormHelper($update);
        $subExternalFieldsDefinition = array(
            'membreId' => array(
                'editionType' => array(
                    'nom' => array('label' => $this->l('nom'))
                ))
        );
        $helper->addExternalFieldDefinition('membreId', ExternalFieldEditionType::ALL_FIELDS, null, $subExternalFieldsDefinition);
        $helper->setAjaxEnabled(true);
        $helper->setActionDefaultOpenMode(true);
        if($update){   
            $this->result->addSuccess('enregistrer avec succes'); 
             
        }
        
        return $helper;
    }
    protected function createListHelper()
    {
        $listHelper = parent::createListHelper();
        $definition = array(
            'otherFields' => array(
                'nom' => array('label' => $this->l('nom'))
            ),
            'hidden' => true
        );
        $listHelper->addExternalFieldDefinition('membreId', $definition);
        $listHelper->setSpecificSearchEnabled(true);
        $listHelper->setInnerSearchEnabled(false);
        $listHelper->setQuickSearchEnabled(true);
        $listHelper->setSpecificSortEnabled(true);
        $listHelper->setAjaxEnabled(true);
        
        
        return $listHelper;
    }
    
   
   
    
}
