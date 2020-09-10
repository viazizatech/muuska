<?php 
/** @var \muuska\html\input\Checkbox $item */
/** @var \muuska\html\config\GlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php echo $item->drawStartTag('label', $globalConfig, $callerConfig, 'kt-checkbox');?>
<input type="checkbox" name="<?php echo $item->getName();?>" value="<?php echo $item->getValue();?>"<?php echo $item->getStringFromCondition($item->isChecked(), 'checked', '', true);?>><?php echo $item->drawString($item->getLabel(), ' ');?>
<span></span>
<?php echo $item->drawEndTag('label', $globalConfig, $callerConfig);?>