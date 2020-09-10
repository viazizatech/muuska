<?php
/** @var \muuska\html\HtmlFieldValue $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'form-group');?>
	<?php echo $item->renderLabel($globalConfig, $callerConfig);?>
	<span class="form-control-plaintext kt-font-bolder"><?php echo $item->renderValue($globalConfig)?></span>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>