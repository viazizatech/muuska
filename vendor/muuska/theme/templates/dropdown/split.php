<?php
/** @var \muuska\html\dropdown\SplitDropdown $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'btn-group');?>
	<?php echo $item->renderDefaultContent($globalConfig, $callerConfig);?>
	<button<?php echo $item->drawButtonClasses($globalConfig, $item->getStringFromCondition($item->isDefaultToggleIconEnabled(), 'dropdown-toggle dropdown-toggle-split'));?> type="button" data-toggle="dropdown"></button>
	<?php echo $item->generateChildrenWithClass($globalConfig, $callerConfig, 'dropdown-item', '<div'.$item->drawMenuClasses($globalConfig, 'dropdown-menu').'>', '</div>');?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>