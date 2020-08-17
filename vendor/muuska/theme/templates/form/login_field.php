<?php 
/** @var \muuska\html\form\FormField $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'input-group'.$item->getStringFromCondition($item->hasError(), 'validated', '', true));?>
	<?php echo $item->renderLabel($globalConfig, $callerConfig);?>
	<?php echo $item->renderInput($globalConfig, $callerConfig);?>
	<?php echo $item->drawError('<div class="error invalid-feedback">', '</div>');?>
	<?php echo $item->renderHelpText($globalConfig, $callerConfig, '<span class="form-text text-muted">', '</span>');?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>