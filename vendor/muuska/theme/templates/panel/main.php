<?php
/** @var \muuska\html\panel\Panel $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php
$mainHeader = $item->renderIcon($globalConfig, $callerConfig, '', ' ');
$mainHeader .= $item->renderTitle($globalConfig, $callerConfig, '<h3 class="kt-subheader__title">', '</h3>');
$mainHeader .= $item->renderSubTitle($globalConfig, $callerConfig, '<span class="kt-subheader__separator kt-subheader__separator--v"></span><span class="kt-subheader__desc">', '</span>');
$mainHeader = $item->renderMainHeaderFromString($globalConfig, $callerConfig, $mainHeader.$item->renderMainHeaders($globalConfig, $callerConfig, '&nbsp;&nbsp;'), '<div class="kt-subheader__main">', '</div>');
$toolContent = $item->renderTools($globalConfig, $callerConfig, '<div class="kt-subheader__toolbar panel_tools">', '</div>');
?>
<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig);?>
	<?php echo $item->renderHeaderFromString($globalConfig, $callerConfig, $mainHeader.$toolContent, '<div class="kt-subheader kt-grid__item"><div class="kt-container kt-container--fluid">', '</div></div>');?>
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<?php echo $item->renderInnerContent($globalConfig, $callerConfig);?>
	<?php echo $item->renderFooters($globalConfig, $callerConfig);?>
	</div>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>