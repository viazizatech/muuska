<?php 
/** @var \muuska\html\form\Form $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php echo $item->drawStartTag('form', $globalConfig, $callerConfig, 'kt-form kt-form kt-form--label-right')?>
	<?php echo $item->drawErrorText('<div class="main_error_block"><div class="kt-alert m-alert--icon alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button> ', '</div></div>');?>
	<?php echo $item->generateChildren($globalConfig, $callerConfig);?>
	<div class="row kt-login__extra">
		<?php echo $item->drawContentsByPosition('extraLeft', $globalConfig, $callerConfig, '<div class="col">', '</div>')?>
		<?php echo $item->drawContentsByPosition('extraRight', $globalConfig, $callerConfig, '<div class="col kt-align-right">', '</div>')?>
	</div>
	<?php echo $item->renderContentList(array($item->getSubmit(), $item->getCancel()), $globalConfig, $callerConfig, 'actions', '<div class="kt-login__actions">', '</div>', $item->createCallerConfig('kt-login__btn-primary'));?>
	<input type="hidden" name="<?php echo $item->getSubmittedText();?>"  value="1"/>
<?php echo $item->drawEndTag('form', $globalConfig, $callerConfig)?>