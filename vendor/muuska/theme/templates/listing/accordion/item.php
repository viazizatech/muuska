<?php
/** @var \muuska\html\listing\item\ListItem $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php echo $item->drawStartTag('li', $globalConfig, $callerConfig, 'item')?>
    <div class="accordion_header">
    	<?php echo $item->renderFieldValueByName('title', $globalConfig, $callerConfig)?>
	</div>
	<div class="accordion_body">
    	<?php echo $item->renderFieldValueByName('description', $globalConfig, $callerConfig)?>
	</div>
<?php echo $item->drawEndTag('li', $globalConfig, $callerConfig)?>