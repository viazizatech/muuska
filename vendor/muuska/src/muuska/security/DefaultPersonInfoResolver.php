<?php
namespace muuska\security;

class DefaultPersonInfoResolver implements PersonInfoResolver
{
    /**
     * @var \muuska\dao\DAO
     */
    protected $dao;
    
    /**
     * @var \muuska\dao\util\SelectionConfig
     */
    protected $selectionConfig;
    
    /**
     * @var string
     */
    protected $authentificationField;
    
    /**
     * @param \muuska\dao\DAO $dao
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @param string $authentificationField
     */
    public function __construct(\muuska\dao\DAO $dao, \muuska\dao\util\SelectionConfig $selectionConfig = null, $authentificationField = null) {
        $this->dao = $dao;
        $this->selectionConfig = $selectionConfig;
        $this->authentificationField = $authentificationField;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\security\PersonInfoResolver::getPersonInfo()
     */
    public function getPersonInfo(AuthentificationInfo $authentificationInfo){
        if($this->selectionConfig === null){
            $this->selectionConfig = $this->dao->createSelectionConfig();
        }
        if(empty($this->authentificationField)){
            $this->authentificationField = 'authentificationId';
        }
        $this->selectionConfig->addRestrictionFieldFromParams($this->authentificationField, $authentificationInfo->getId());
        return $this->dao->getUniqueModel($this->selectionConfig, false);
    }
}