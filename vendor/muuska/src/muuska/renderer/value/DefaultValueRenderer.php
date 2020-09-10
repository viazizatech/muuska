<?php
namespace muuska\renderer\value;
use muuska\util\FunctionCallback;

class DefaultValueRenderer extends FunctionCallback implements ValueRenderer{
    /**
     * {@inheritDoc}
     * @see \muuska\renderer\value\ValueRenderer::renderValue()
     */
    public function renderValue($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        $result = null;
        if ($this->callback !== null) {
            if(empty($this->initialParams)){
                $result = call_user_func($this->callback, $data, $globalConfig, $callerConfig);
            }else{
                $result = call_user_func($this->callback, $this->initialParams, $data, $globalConfig, $callerConfig);
            }
        }
        return $result;
    }
}