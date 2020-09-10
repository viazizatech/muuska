<?php
namespace muuska\getter\model;

class ModelPresentationGetter extends AbstractModelGetter
{
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data)
    {
        $result = null;
        $model = $this->getFinalModel($data);
        if($model !== null){
            $result = $this->modelDefinition->getModelPresentation($model);
        }
        return $result;
    }
}

