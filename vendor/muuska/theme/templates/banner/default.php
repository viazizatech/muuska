<?php 
/** @var \muuska\html\Banner $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'banner clearfix');?>
	<?php if($item->hasMainImage()):?>
		<?php if($item->hasTitle()):?>
    		<div class="banner_text">
    			<?php echo $item->renderString($item->getTitle(), $globalConfig, $callerConfig, 'title', '<div class="banner_title">', '</div>');?>
    			<?php echo $item->renderString($item->getSubTitle(), $globalConfig, $callerConfig, 'sub_title', '<div class="banner_sub_title">', '</div>');?>
    			<?php echo $item->renderContent($item->getMainLink(), $globalConfig, $callerConfig, 'main_link', '<div class="banner_link">', '</div>');?>
    		</div>
    		<?php echo $item->renderContent($item->getMainImage(), $globalConfig, $callerConfig, 'main_image', '<div class="banner_image">', '</div>');?>
		<?php else:?>
			<?php echo $item->renderContent($item->getMainImage(), $globalConfig, $callerConfig, 'main_image', '<div class="banner_image">', '</div>');?>
		<?php endif;?>
	<?php else:?>
	<div class="banner_text">
		<?php echo $item->renderString($item->getTitle(), $globalConfig, $callerConfig, 'title', '<div class="banner_title">', '</div>');?>
		<?php echo $item->renderString($item->getSubTitle(), $globalConfig, $callerConfig, 'sub_title', '<div class="banner_sub_title">', '</div>');?>
		<?php echo $item->renderContent($item->getMainLink(), $globalConfig, $callerConfig, 'main_link', '<div class="banner_link">', '</div>');?>
	</div>
	<?php endif;?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>