<?php
namespace myapp\model;

use muuska\model\AbstractModel;

class Emprunt extends AbstractModel{
	protected $id;
    protected $membreId;
    protected $montant_emprunt;
    protected $taux_emprunt;
    protected $date_emprunt;
    
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
	public function getMontant_emprunt(){
		return $this->montant_emprunt;
	}
	public function setMontant_emprunt($montant_emprunt){
        $this->montant_emprunt= $montant_emprunt;
      
    }
	public function getTaux_emprunt(){
		return $this->taux_emprunt;
	}
	public function setTaux_emprunt($taux_emprunt){
		$this->taux_emprunt = $taux_emprunt;
    }
    public function getDate_emprunt(){
		return $this->date_emprunt;
	}
	public function setDate_emprunt($date_emprunt){
		$this->date_emprunt = $date_emprunt;
    }
    

    //methode pour calculer l'interet generer par les epargnes au fil des seance

    public function getVerifEmprunt($total_epargne){
        if($this->montant_emprunt < $total_epargne){
            return ('vous pouvez empreinter');
        }else { return('desolez pas assez d\' argent');}
        
    }
    public function getInteret_epargne(){
		return $this->interet_epargne;
	}
	public function setInteret_epargne($interet_epargne){
		$this->date_epagne = $interet_epargne;
    }
    // methode pour calculer les epagne totales d'un membre sans interets

	public function getTotalMontant($montant_emprunt){
         $emprunt = $montant_emprunt+= $this->montant_emprunt;
          return  $emprunt;
       
    }
     //methode pour calculer le montant des epargnes totales des membres generés par les epargnes au fil des seances
    public function getEpargneGlobal($id,$membreId,$type_epargne,$date_epagne,$epargne_total_membre,$interet_Total){
		
	}
	

    
}