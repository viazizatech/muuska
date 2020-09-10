<?php
namespace muuska\getter\model;

class MultipleModelGetter extends AbstractModelGetter
{
    /**
     * @var string
     */
    protected $associationName;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $associationName
     * @param \muuska\getter\Getter $finalModelGetter
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, $associationName, \muuska\getter\Getter $finalModelGetter = null){
        parent::__construct($modelDefinition, $finalModelGetter);
        $this->associationName = $associationName;
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
            $result = $this->modelDefinition->getMultipleAssociatedModels($model, $this->associationName);
        }
        return $result;
    }
}
