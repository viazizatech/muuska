<?php 
/** @var \muuska\html\input\DefaultHtmlInput $item */
/** @var \muuska\html\config\GlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<input<?php echo $item->drawAllAttributes($globalConfig, $callerConfig, 'form-control');?> />