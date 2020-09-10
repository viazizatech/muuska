<?php 
/** @var \muuska\html\input\Select2 $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<select<?php echo $item->drawAllAttributes($globalConfig, $callerConfig, 'form-control kt-select2');?>>
	<?php echo $item->renderInnerContent($globalConfig, $callerConfig);?>
</select>