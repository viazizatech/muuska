<?php
namespace myapp\model;

use muuska\model\AbstractModel;

class Utilisateur extends AbstractModel{
	protected $id;
    protected $nom;
    protected $prenom;
	
    public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function setNom($nom){
		return $this->nom = $nom;
	}
	public function getNom(){
		return $this->nom ;
	}
	public function getPrenom(){
		return $this->prenom;
    }
    public function setPrenom($prenom){
		return $this->prenom = $prenom;
	}
}