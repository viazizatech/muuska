<?php
/** @var \muuska\html\listing\ListLimiterSwitcher $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'dropdown limiter_switcher no_min_width')?>
	<button class="btn btn-label-brand dropdown-toggle" data-toggle="dropdown" type="button">
		<?php echo $item->getSelectedLabel();?>
	</button>
	<?php if($item->hasOptionProvider()):?>
	<div class="dropdown-menu">
		<?php $optionProvider = $item->getOptionProvider();?>
		<?php $options = $optionProvider->getOptions();?>
		<?php foreach($options as $option):?>
			<a href="<?php echo $item->getOptionUrl($option);?>" title="<?php echo $option->getLabel();?>"<?php echo $item->drawLinkClasses('dropdown-item', true, true);?>><?php echo $option->getLabel();?></a>
		<?php endforeach;?>
	</div>
	<?php endif;?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig)?>