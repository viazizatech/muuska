<?php
/** @var \muuska\html\listing\Accordion $item */
/** @var \muuska\html\config\GlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('ul', $globalConfig, $callerConfig, 'msk_accordion has_icon')?>
<?php echo $item->drawItems($globalConfig, $callerConfig);?>
<?php echo $item->drawEndTag('ul', $globalConfig, $callerConfig)?>