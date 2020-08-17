<?php
/** @var \muuska\html\alert\HtmlAlert $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, '', null, array('role' => 'alert'))?>
	<?php echo $item->renderIcon($globalConfig, $callerConfig, '<div class="alert-icon">', '</div>')?>
	<div class="alert-text">
		<?php echo $item->renderTitle($globalConfig, $callerConfig, '<h4 class="alert-heading">', '</h4>')?>
		<?php echo $item->renderAlerts('<p>', '</p>')?>
		<?php echo $item->renderFooterContent($globalConfig, $callerConfig, '<hr><p class="mb-0">', '</p>')?>
	</div>
	<?php if($item->isCloseButtonEnabled()):?>
	<div class="alert-close">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="la la-close"></i></span></button>
	</div>
	<?php endif;?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig)?>