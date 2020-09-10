<?php
namespace muuska\option;
interface Option{
	/**
	 * @return mixed
	 */
	public function getValue();
	
	/**
	 * @return string
	 */
	public function getLabel();
}