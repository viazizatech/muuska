<?php
namespace myapp\model;

use muuska\model\AbstractModel;

class Association extends AbstractModel{
	protected $id;
    protected $accessibility;
    protected $nom_assoc;
    protected $siege;
    protected $telephone;
    protected $email_respon;
	protected $logo;
    protected $nom_admin;	
    protected $prenom_admin;	
    protected $devise;
    protected $mot_pass;
    protected $con_mot_pass;
    protected $status;	

	public function getId(){
        return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function getNom_assoc(){
		return $this->nom_assoc;
	}
	public function setNom_assoc($nom_assoc){
        $this->nom_assoc = $nom_assoc;
      
    }
	public function getSiege(){
		return $this->siege;
	}
	public function setSiege($siege){
        $this->siege = $siege;
    }
    
	public function getPhone_respon(){
		return $this->phone_respon;
	}
	public function setPhone_respon($telephone){
		$this->phone_respon = $telephone;
    }
    public function getEmail_respon(){
		return $this->email_respon;
	}
	public function setEmail_respon($email_respon){
		$this->email_respon = $email_respon;
	}
	public function getNom_Admin(){
		return $this->nom_admin;
	}
	public function setNom_Admin($nom_admin){
		$this->nom_admin = $nom_admin;
    }
    public function getPrenom_Admin(){
		return $this->prenom_admin;
	}
	public function setPrenom_Admin($prenom_admin){
		$this->prenom_admin= $prenom_admin;
	}
	public function getLogo(){
		return $this->logo;
	}
	public function setLogo($logo){
		$this->logo = $logo;
	}
	public function getDevise(){
		return $this->devise;
	}
	public function setDevise($devise){
		$this->devise = $devise;
    }
    public function getMot_pass(){
		return $this->mot_pass;
	}
	public function setMot_pass($mot_pass){
		$this->mot_pass= $mot_pass;
    }
    public function getStatus(){
		return $this->status;
	}
	public function setStatus($status){
		$this->status = $status;
    }


    public function getAccessibility(){
        return $this->accessibility;
    }
    public function setAccessibility($accessibility){
        $this->accessibility = $accessibility;
    }
    
}