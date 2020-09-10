<?php 
/** @var \muuska\html\form\Form $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php 
$itemsPerGroup = (int)$item->getExtra('itemsPerGroup', 3);
$colIndex = (int)(($itemsPerGroup > 0) ? 12 / $itemsPerGroup : 12);
$colClass = ($colIndex > 0) ? 'col-lg-'.$colIndex : 'col-lg-1';
$splittedItems = $item->splitChildren($itemsPerGroup);
?>
<?php echo $item->drawStartTag('form', $globalConfig, $callerConfig, 'children_container kt-form kt-form--label-right')?>
	<div class="kt-portlet">
		<?php echo $item->renderString($item->getLabel(), $globalConfig, $callerConfig, 'label', '<div class="kt-portlet__head"><div class="kt-portlet__head-label"><h3 class="kt-portlet__head-title">', '</h3></div></div>');?>
    	<div class="kt-portlet__body">
    		<?php echo $item->drawErrorText('<div class="main_error_block"><div class="kt-alert m-alert--icon alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button> ', '</div></div>');?>
    		<?php foreach($splittedItems as $children):?>
				<?php echo $item->renderContentList($children, $globalConfig, $callerConfig, 'children', '<div class="form-group row">', '</div>', $item->createCallerConfig($colClass, null, null, null, null, array('form-group')))?>
			<?php endforeach;?>
    	</div>
    	<?php if($item->hasFooter()):?>
    	<div class="kt-portlet__foot">
			<div class="row kt-form__actions">
				<div class="col-lg-4"></div>
				<div class="col-lg-8">
				<?php echo $item->renderContent($item->getSubmit(), $globalConfig, $callerConfig, 'submit');?>
				<?php echo $item->renderContent($item->getCancel(), $globalConfig, $callerConfig, 'cancel', '&nbsp;&nbsp;');?>
				</div>
			</div>
    	</div>
    	<?php endif;?>
	</div>
	<input type="hidden" name="<?php echo $item->getSubmittedText();?>"  value="1"/>
<?php echo $item->drawEndTag('form', $globalConfig, $callerConfig)?>