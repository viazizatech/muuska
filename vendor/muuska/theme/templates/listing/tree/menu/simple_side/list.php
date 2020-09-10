<?php
/** @var \muuska\html\listing\tree\HtmlTree $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('ul', $globalConfig, $callerConfig, 'menu_list')?>
<?php echo $item->drawItems($globalConfig, $callerConfig);?>
<?php echo $item->drawEndTag('ul', $globalConfig, $callerConfig)?>