<?php 
/** @var \muuska\html\input\UploadPreview $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php if($item->isUseAsTemplate()):?>
<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'preview_item');?>
	<div class="file_preview">[filePreviewContent]</div>
	<input type="hidden" data-name="<?php echo $item->getName();?>" value="[fileValue]" class="file_value_input" />
	<div class="actions">
		<a href="#" class="btn_edit" title="Edit"><i class="fa fa-edit"></i></a>
		<a href="#" class="btn_remove" title="Remove"><i class="fa fa-times"></i></a>
	</div>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>
<?php else:?>
<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'preview_item'.$item->getStringFromCondition($item->isFileSaved(), 'saved', '', true));?>
	<div class="file_preview"><?php echo $item->getFilePreview();?></div>
	<input type="hidden" name="<?php echo $item->getName();?>" value="<?php echo $item->getValue();?>" class="file_value_input" />
	<div class="actions">
		<a href="#" class="btn_edit" title="Edit"><i class="fa fa-edit"></i></a>
		<a href="#" class="btn_remove" title="Remove"><i class="fa fa-times"></i></a>
	</div>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>
<?php endif;?>