<?php 
/** @var \muuska\html\ChildrenContainer $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'kt-portlet children_container kt-form kt-form--label-right')?>
	<?php echo $item->renderString($item->getLabel(), $globalConfig, $callerConfig, 'label', '<div class="kt-portlet__head"><div class="kt-portlet__head-label"><h3 class="kt-portlet__head-title">', '</h3></div></div>');?>
	<div class="kt-portlet__body">
		<?php echo $item->generateChildren($globalConfig, $callerConfig);?>
	</div>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig)?>