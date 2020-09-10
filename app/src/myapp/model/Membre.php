<?php
namespace myapp\model;

use muuska\model\AbstractModel;

class Membre extends AbstractModel{
	protected $id;
    protected $nom;
    protected $prenom;
	protected $ville;
	protected $pays;
	protected $photo;
    protected $addresse;
	protected $email;	
	protected $telephone;

	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function setName($nom){
		$this->nom = $nom;
	}
	public function getNom(){
		$this->nom ;
	}
	public function getPrenom(){
		return $this->prenom;
    }
    public function setPrenom($prenom){
		return $this->prenom = $prenom;
	}
	public function getville(){
		return $this->ville;
	}
	public function setville($ville){
		$this->ville = $ville;
	}
	public function getpays(){
		return $this->pays;
	}
	public function setAccessibility($pays){
		$this->pays = $pays;
	}
	public function getTelephone(){
		$this->telephone ;
	}
	public function setTelephone($telephone){
		$this->telephone=$telephone ;
	}
	public function getaddresse(){
		return $this->addresse;
	}
	public function setAddresse($addresse){
		$this->description = $addresse;
    }
	public function getPhoto(){
		return $this->photo;
	}
	public function setPhoto($photo){
		$this->image = $photo;
	}
}