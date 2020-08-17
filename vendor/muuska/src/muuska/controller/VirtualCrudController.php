<?php
namespace muuska\controller;

class VirtualCrudController extends CrudController
{	
    /**
     * @var \muuska\model\ModelDefinition
     */
    protected $modelDefinition;
    
    /**
     * @var array
     */
    protected $virtualDefinition;
    
    /**
     * @param \muuska\controller\ControllerInput $input
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param array $virtualDefinition
     */
    public function __construct(\muuska\controller\ControllerInput $input, \muuska\model\ModelDefinition $modelDefinition, $virtualDefinition = null)
    {
        parent::__construct($input);
		$this->modelDefinition = $modelDefinition;
		$this->virtualDefinition = $virtualDefinition;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\CrudController::createFormHelper()
     */
    protected function createFormHelper($update) {
        $formHelper = parent::createFormHelper($update);
        if(isset($this->virtualDefinition['form']) && isset($this->virtualDefinition['form']['externalFieldsDefinition'])){
            $formHelper->setExternalFieldsDefinition($this->virtualDefinition['form']['externalFieldsDefinition']);
        }
        return $formHelper;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\CrudController::createListHelper()
     */
    protected function createListHelper(){
        $listHelper = parent::createListHelper();
        if(isset($this->virtualDefinition['list']) && isset($this->virtualDefinition['list']['externalFieldsDefinition'])){
            $listHelper->setExternalFieldsDefinition($this->virtualDefinition['list']['externalFieldsDefinition']);
        }
        return $listHelper;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\CrudController::createViewHelper()
     */
    protected function createViewHelper(){
        $viewHelper = parent::createViewHelper();
        if(isset($this->virtualDefinition['list']) && isset($this->virtualDefinition['view']['externalFieldsDefinition'])){
            $viewHelper->setExternalFieldsDefinition($this->virtualDefinition['view']['externalFieldsDefinition']);
        }
        return $viewHelper;
    }
}