<?php
namespace muuska\getter\model;

use muuska\getter\Getter;

abstract class AbstractModelGetter implements Getter
{
    /**
     * @var \muuska\model\ModelDefinition
     */
    protected $modelDefinition;
    
    /**
     * @var Getter
     */
    protected $finalModelGetter;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $field
     * @param Getter $finalModelGetter
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, Getter $finalModelGetter = null){
        $this->modelDefinition = $modelDefinition;
        $this->finalModelGetter = $finalModelGetter;
    }
    
    /**
     * @param mixed $data
     * @return object
     */
    public function getFinalModel($data) {
        $result = $data;
        if($this->finalModelGetter !== null){
            $result = ($data !== null) ? $this->finalModelGetter->get($data) : null;
        }
        return $result;
    }
    
    /**
     * @return \muuska\model\ModelDefinition
     */
    public function getModelDefinition() {
        return $this->modelDefinition;
    }
}
