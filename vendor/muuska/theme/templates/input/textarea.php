<?php 
/** @var \muuska\html\input\Textarea $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<textarea<?php echo $item->drawAllAttributes($globalConfig, $callerConfig, 'form-control');?>><?php echo $item->getValue()?></textarea>