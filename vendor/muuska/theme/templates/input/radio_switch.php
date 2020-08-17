<?php
/** @var \muuska\html\input\RadioSwitch $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('span', $globalConfig, $callerConfig, 'radio-switch');?>
	<?php if($item->hasOptionProvider()):?>
		<?php $optionProvider = $item->getOptionProvider();?>
		<?php $options = $optionProvider->getOptions();?>
		<?php foreach($options as $option):?>
			<?php $optionId = $item->getOptionId($option->getValue());?>
			<input id="<?php echo $optionId;?>" type="radio" name="<?php echo $item->getName();?>" value="<?php echo $option->getValue();?>"<?php echo $item->getStringFromCondition($item->isOptionSelected($option), 'checked="checked"', '', true);?>/>
    		<label for="<?php echo $optionId;?>"><?php echo $option->getLabel();?></label>
        <?php endforeach;;?>
	<?php endif;?>
	<span class="slide-button"></span>
<?php echo $item->drawEndTag('span', $globalConfig, $callerConfig);?>