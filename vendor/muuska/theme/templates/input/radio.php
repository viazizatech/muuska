<?php
/** @var \muuska\html\input\Radio $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, $item->getStringFromCondition($item->isInline(), 'kt-radio-inline', 'kt-radio-list'));?>
	<?php $options = $item->getOptions();?>
	<?php foreach($options as $option):?>
		<label class="kt-radio">
    		<input type="radio" name="<?php echo $item->getName();?>" value="<?php echo $option->getValue();?>"<?php echo $item->getStringFromCondition($item->isOptionSelected($option), 'checked="checked"', '', true);?>/> <?php echo $option->getLabel();?>
    		<span></span>
    	</label>
	<?php endforeach;?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>