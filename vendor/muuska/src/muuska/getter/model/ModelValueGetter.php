<?php
namespace muuska\getter\model;

class ModelValueGetter extends AbstractModelGetter
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
            $result = $this->modelDefinition->getPropertyValue($model, $this->field);
        }
        return $result;
    }
}
