<?php
namespace muuska\getter\model;

class ModelLangValueGetter extends AbstractModelGetter
{
    /**
     * @var string
     */
    protected $field;
    
    /**
     * @var string
     */
    protected $lang;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $field
     * @param string $lang
     * @param \muuska\getter\Getter $finalModelGetter
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, $field, $lang, \muuska\getter\Getter $finalModelGetter = null){
        parent::__construct($modelDefinition, $finalModelGetter);
        $this->field = $field;
        $this->lang = $lang;
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
            $result = $this->modelDefinition->getPropertyValueByLang($model, $this->field, $this->lang);
        }
        return $result;
    }
}

