<?php
namespace myapp\model;

use muuska\model\AbstractModel;

class Library extends AbstractModel{
	protected $id;
	protected $addressId;
	protected $name;
	protected $openingTime;
	protected $accessibility;
	protected $description;
	protected $image;
		

	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function getAddressId(){
		return $this->addressId;
	}
	public function setAddressId($addressId){
		$this->addressId = $addressId;
	}
	public function getName(){
		return $this->name;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function getOpeningTime(){
		return $this->openingTime;
	}
	public function setOpeningTime($openingTime){
		$this->openingTime = $openingTime;
	}
	public function getAccessibility(){
		return $this->accessibility;
	}
	public function setAccessibility($accessibility){
		$this->accessibility = $accessibility;
	}
	public function getImage(){
		return $this->image;
	}
	public function setImage($image){
		$this->image = $image;
	}
	public function getDescription(){
		return $this->description;
	}
	public function setDescription($description){
		$this->description = $description;
	}
}