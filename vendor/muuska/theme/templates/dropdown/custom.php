<?php 
/** @var \muuska\html\dropdown\CustomDropdown $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'dropdown');?>
	<?php echo $item->renderContent($item->getDropdownToggle(), $globalConfig, $callerConfig, 'dropdownToggle', '', '', $item->createCallerConfig(null, null, array('data-toggle' => 'dropdown')));?>
	<?php echo $item->generateChildrenWithClass($globalConfig, $callerConfig, 'dropdown-item', '<div'.$item->drawMenuClasses($globalConfig, 'dropdown-menu').'>', '</div>');?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>