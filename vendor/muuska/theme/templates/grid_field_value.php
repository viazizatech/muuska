<?php
/** @var \muuska\html\HtmlGridFieldValue $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'form-group row');?>
	<?php echo $item->renderLabelWithGridClasses($globalConfig, $callerConfig, 'col-form-label');?>
	<?php echo $item->renderValueWithGridClasses($globalConfig, null, '<span class="form-control-plaintext kt-font-bolder">', '</span>');?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>