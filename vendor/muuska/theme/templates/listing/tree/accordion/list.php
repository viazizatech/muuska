<?php
/** @var \muuska\html\listing\tree\HtmlTree $item */
/** @var \muuska\html\config\GlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('ul', $globalConfig, $callerConfig, 'accordion_tree msk_accordion has_specific_trigger has_icon'.$item->getStringFromCondition($item->hasActions(), ' has_actions'))?>
<?php echo $item->drawItems($globalConfig, $callerConfig);?>
<?php echo $item->drawEndTag('ul', $globalConfig, $callerConfig)?>