<?php
namespace muuska\getter\model;

use muuska\getter\Getter;

class ModelAllLangsValueGetter extends AbstractModelGetter implements Getter
{
    /**
     * @var string
     */
    protected $field;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $field
     * @param Getter $finalModelGetter
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, $field, Getter $finalModelGetter = null){
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
            $result = $this->modelDefinition->getAllLangsPropertyValues($model, $this->field);
        }
        return $result;
    }
}

