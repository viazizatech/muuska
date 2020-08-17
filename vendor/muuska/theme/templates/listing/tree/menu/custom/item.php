<?php
/** @var \muuska\html\listing\tree\TreeItem $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php $defaultLink = $item->getDefaultLink($globalConfig, $callerConfig);?>
<?php echo $item->drawStartTag('li', $globalConfig, $callerConfig, 'item')?>
<?php echo $item->drawDefaultLinkStartTag($defaultLink, $globalConfig)?>
<?php echo $item->renderTitle($globalConfig, $callerConfig, 'span', null, 'text');?>
<?php echo $item->drawDefaultLinkEndTag($defaultLink, $globalConfig);?>
<?php echo $item->drawItems($globalConfig, $callerConfig, '<ul class="sub_menu">', '</ul>')?>
<?php echo $item->drawEndTag('li', $globalConfig, $callerConfig)?>