<?php 
/** @var \muuska\html\form\QuickSearchForm $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php echo $item->drawStartTag('form', $globalConfig, $callerConfig, 'quick_search_form')?>
	<?php if($item->isIconEnabled()):?>
	<div class="kt-input-icon kt-input-icon--<?php echo $item->drawClassForIcon('left', 'right')?>">
		<?php echo $item->generateChildren($globalConfig, $callerConfig);?>
		<span class="kt-input-icon__icon kt-input-icon__icon--<?php echo $item->drawClassForIcon('left', 'right')?>">
			<span><?php echo $item->renderIcon($globalConfig, $callerConfig, '<i class="la la-search"></i>');?></span>
		</span>
	</div>
	<?php else:?>
		<?php echo $item->generateChildren($globalConfig, $callerConfig);?>
	<?php endif;?>
	<input type="hidden" name="<?php echo $item->getSubmittedText();?>"  value="1"/>
<?php echo $item->drawEndTag('form', $globalConfig, $callerConfig)?>