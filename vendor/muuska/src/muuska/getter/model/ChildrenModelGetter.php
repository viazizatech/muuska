<?php
namespace muuska\getter\model;

class ChildrenModelGetter extends AbstractModelGetter
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
     * @param \muuska\dao\DAO $dao
     * @param \muuska\dao\util\SelectionConfig $selectionConfig
     * @param \muuska\getter\Getter $finalModelGetter
     */
    public function __construct(\muuska\dao\DAO $dao, \muuska\dao\util\SelectionConfig $selectionConfig = null, \muuska\getter\Getter $finalModelGetter = null){
        parent::__construct($dao->getModelDefinition(), $finalModelGetter);
        $this->dao = $dao;
        $this->selectionConfig = $selectionConfig;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data)
    {
        $result = array();
        $model = $this->getFinalModel($data);
        if($model !== null){
            $result = $this->dao->getChildren($model, $this->selectionConfig)->toArray();
        }
        return $result;
    }
}
