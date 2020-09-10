<?php 
/** @var \muuska\html\ChildrenContainer $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'btn-toolbar');?>
	<?php echo $item->generateChildren($globalConfig, $callerConfig)?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>