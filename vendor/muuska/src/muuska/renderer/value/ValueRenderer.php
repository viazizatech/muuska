<?php
namespace muuska\renderer\value;
interface ValueRenderer{
    /**
	 * @param mixed $data
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string
	 */
    public function renderValue($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null);
}