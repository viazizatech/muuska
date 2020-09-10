<?php
namespace muuska\dao\util;

class MultipleSelectionAssociation extends SelectionConfig
{
    /**
     * @var string
     */
    protected $associationName;
    
	
    /**
     * @param string $associationName
     * @param string $lang
     */
    public function __construct($associationName, $lang = '') {
        parent::__construct($lang);
        $this->setAssociationName($associationName);
	}
	
    /**
     * @return string
     */
    public function getAssociationName()
    {
        return $this->associationName;
    }

    /**
     * @param string $associationName
     */
    public function setAssociationName($associationName)
    {
        $this->associationName = $associationName;
    }
}