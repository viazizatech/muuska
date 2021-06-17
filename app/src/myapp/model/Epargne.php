<?php
namespace myapp\model;

use muuska\model\AbstractModel;

class Epargne extends AbstractModel{
	protected $id;
    protected $membreId;
    protected $montant_epargne;
    protected $type_epargne;
    protected $taux_epargne;
    protected $date_epagne;
    protected $epargne_total_membre;
    protected $epargneglobal;
    protected $interet_epargne;

	public function getId(){
        return $this->id;
	}
	public function setId($id){
		$this->id = $id;
    }
    public function getMembreId(){
		return $this->addressId;
	}
	public function setMembreId($membreId){
		$this->addressId = $membreId;
	}
	public function getMontant_epargne(){
		return $this->montant_epargne;
	}
	public function setMontant_epargne($montant_epargne){
        $this->montant_epargne = $montant_epargne;
      
    }
	public function getType_epargne(){
		return $this->type_epargne;
	}
	public function setType_epargne($type_epargne){
        $this->type_epargne = $type_epargne;
    }
    
	public function getTaux_epargne(){
		return $this->taux_epargne;
	}
	public function setTaux_epargne($taux_epargne){
		$this->taux_epargne = $taux_epargne;
    }
    public function getDate_epagne(){
		return $this->date_epagne;
	}
	public function setDate_epagne($date_epagne){
		$this->date_epagne = $date_epagne;
    }
    

    //methode pour calculer l'interet generer par les epargnes au fil des seance

    public function getInteret(){
        return  $this->montant_epargne *= 1 +  $this->taux_epargne;
        
    }
    public function getInteret_epargne(){
		return $this->interet_epargne;
	}
	public function setInteret_epargne($interet_epargne){
		$this->date_epagne = $interet_epargne;
    }
    // methode pour calculer les epagne totales d'un membre sans interets

	public function getTotalMontant($montant_epargne ){
         $epargne = $montant_epargne += $this->montant_epargne;
          return  $epargne;
       
    }
     //methode pour calculer le montant des epargnes totales des membres generés par les epargnes au fil des seances
    public function getEpargneGlobal($id,$membreId,$type_epargne,$date_epagne,$epargne_total_membre,$interet_Total){
		
	}
	

    
}