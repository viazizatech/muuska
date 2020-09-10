<?php
/** @var \muuska\html\listing\tree\HtmlTree $item */
/** @var \muuska\html\config\GlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('ul', $globalConfig, $callerConfig, 'custom_tree'.$item->getStringFromCondition($item->hasActions(), ' has_actions'))?>
<?php echo $item->drawItems($globalConfig, $callerConfig);?>
<?php echo $item->drawEndTag('ul', $globalConfig, $callerConfig)?>