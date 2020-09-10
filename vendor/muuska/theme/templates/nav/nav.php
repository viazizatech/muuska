<?php 
/** @var \muuska\html\nav\HtmlNav $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('ul', $globalConfig, $callerConfig, 'kt-nav');?>
	<?php echo $item->renderItems($globalConfig, $callerConfig);?>
<?php echo $item->drawEndTag('ul', $globalConfig, $callerConfig);?>