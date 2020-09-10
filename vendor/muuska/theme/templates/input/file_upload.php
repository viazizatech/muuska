<?php 
/** @var \muuska\html\input\FileUpload $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
/** @var \muuska\translation\LangTranslator $translator */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'upload_parent_block validated'.$item->drawHasPreviews('has_preview', '', true));?>
	<div class="upload-box-drop has-advanced-upload">
		<button type="button" class="btn btn-default btn_select_file">
			<i class="fa fa-folder-open"></i> <?php echo $translator->l('Select a file...');?>
		</button>
		<span><?php echo $translator->l('Or drag it here');?></span>
	</div>
	<div class="preview_zone">
	<?php echo $item->renderPreviews($globalConfig, $callerConfig);?>
	</div>
	<?php echo $item->renderPreviewTemplate($globalConfig, $callerConfig, '<div class="preview_template" style="display:none;">', '</div>');?>
	<div class="error invalid-feedback field_error_block"></div>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>