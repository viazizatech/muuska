<?php 
/** @var \muuska\html\HtmlElement $item */
/** @var \muuska\html\form\FormField $langField */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php $activeLang = $item->getExtra('activeLang');?>
<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig);?>
	<button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" type="button">
		<span class="current_lang_field_switcher_label"><?php echo $item->getExtra('activeLangLabel');?></span>
	</button>
	<?php $languages = $item->getExtra('languages', array());?>
	
	<ul class="dropdown-menu">
		<?php foreach($languages as $langField):?>
			<?php $lang = $langField->getName();?>
			<?php $activeClass = $item->getStringFromCondition(($activeLang == $lang), 'active', '', true);?>
			<li class="dropdown-item lang_field_switcher<?php echo $activeClass;?>" data-lang="<?php echo $lang;?>" data-label="<?php echo $langField->getExtra('shortLabel', $lang);?>"><?php echo $langField->renderLabel($globalConfig, $callerConfig);?></li>
		<?php endforeach;?>
	</ul>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>