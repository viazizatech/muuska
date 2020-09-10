<?php
/** @var \muuska\html\form\GridFormField $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'form-group row'.$item->getStringFromCondition($item->hasError(), 'validated', '', true));?>
	<?php echo $item->renderLabelWithGridClasses($globalConfig, $callerConfig, 'col-form-label');?>
	<?php echo $item->renderInputWithGridClasses($globalConfig, $callerConfig, '', $item->drawError('<div class="error invalid-feedback">', '</div>').$item->renderHelpText($globalConfig, $callerConfig, '<span class="form-text text-muted">', '</span>'));?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>