<?php
namespace myapp\controller\admin\partial;

use muuska\controller\CrudController;
use myapp\model\EpargneDefinition;
use myapp\model\AnnonceDefinition;
use muuska\constants\ExternalFieldEditionType;

class CrudAdminController extends CrudController
{
    protected function TotalEpargne(){
        $dao = $this->input->getDAO(EpargneDefinition::getInstance());
        $data = $dao->getData();
        $montant_epargne = 0;
        foreach ($data as $epargne){        
         $total_epargne = $montant_epargne += (float)$epargne->getPropertyValue('montant_epargne') ;
        }
        print_r($total_epargne);
        return $total_epargne;    
    } 
    
}
