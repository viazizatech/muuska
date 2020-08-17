<?php
namespace muuska\renderer\value;

use muuska\constants\FieldNature;
use muuska\util\App;

class ModelFileRenderer implements ValueRenderer{
    /**
     * @var string
     */
    protected $field;
    
    /**
     * @var \muuska\getter\Getter
     */
    protected $finalModelGetter;
    
    /**
     * @var \muuska\model\ModelDefinition
     */
    protected $modelDefinition;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $field
     * @param \muuska\getter\Getter $finalModelGetter
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition, $field, \muuska\getter\Getter $finalModelGetter = null){
        $this->modelDefinition = $modelDefinition;
        $this->finalModelGetter = $finalModelGetter;
        $this->field = $field;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\value\ValueRenderer::renderValue()
     */
    public function renderValue($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        $result = '';
        $model = $this->getFinalModel($data);
        
        if ($model !== null) {
            $shortFileName = $this->modelDefinition->getPropertyValue($model, $this->field);
            if (!empty($shortFileName)) {
                $fieldDefinition = $this->modelDefinition->getFieldDefinition($this->field);
                $url = App::getApp()->getModelFileUrl($this->modelDefinition, $model, $this->field);
                if(isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::IMAGE)){
                    $result = App::htmls()->createHtmlImage($url, $this->field)->generate($globalConfig, $callerConfig);
                }else{
                    $fileLocation = App::getApp()->getModelFullFile($this->modelDefinition, $model, $this->field);
                    $result = $globalConfig->hasTheme() ? $globalConfig->getTheme()->getFilePreview($url, $fileLocation, $shortFileName) : App::getApp()->getFilePreview(App::getDefaultSubAppName(), $url, $fileLocation, $shortFileName);
                }
            }
        }
        return $result;
    }
    
    /**
     * @param mixed $data
     * @return object
     */
    public function getFinalModel($data) {
        return (($data !== null) && ($this->finalModelGetter !== null)) ? $this->finalModelGetter->get($data) : $data;
    }
}