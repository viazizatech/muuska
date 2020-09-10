<?php 
/** @var \muuska\html\input\InputIcon $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php echo $item->drawStartTag('form', $globalConfig, $callerConfig, 'kt-input-icon kt-input-icon--'.$item->drawClassForIcon('left', 'right'))?>
	<?php echo $item->renderInput($globalConfig, $callerConfig);?>
	<span class="kt-input-icon__icon kt-input-icon__icon--<?php echo $item->drawClassForIcon('left', 'right')?>">
		<span><?php echo $item->renderIcon($globalConfig, $callerConfig)?></span>
	</span>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig)?>