<?php
namespace muuska\html\input;

class InputHidden extends HtmlInput{
    /**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct($name, $value = null) {
	    parent::__construct('hidden', $name, $value);
	}
}