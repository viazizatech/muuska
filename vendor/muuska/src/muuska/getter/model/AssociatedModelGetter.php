<?php
namespace muuska\getter\model;

use muuska\util\App;

class AssociatedModelGetter extends AbstractModelGetter
{
    /**
     * @var string
     */
    protected $field;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $field
     * @param \muuska\getter\Getter $finalModelGetter
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, $field, \muuska\getter\Getter $finalModelGetter = null){
        parent::__construct($modelDefinition, $finalModelGetter);
        $this->field = $field;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data)
    {
        $result = null;
        $model = $this->getFinalModel($data);
        if($model !== null){
            $result = $this->modelDefinition->getAssociatedModel($model, $this->field);
        }
        return $result;
    }
    
    /**
     * @param string $subField
     * @return \muuska\getter\model\AssociatedModelGetter
     */
    public function createNew($subField)
    {
        return App::getters()->createAssociatedModelGetter($this->modelDefinition->getAssociationDefinition($this->field), $subField, $this);
    }
}
