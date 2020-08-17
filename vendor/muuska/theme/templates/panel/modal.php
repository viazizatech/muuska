<?php 
/** @var \muuska\html\panel\Panel $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php
$mainHeader = $item->renderIcon($globalConfig, $callerConfig, '<span class="kt-portlet__head-icon">', '</span>');
$mainHeader .= $item->renderTitle($globalConfig, $callerConfig, '<h3 class="kt-portlet__head-title">', $item->renderSubTitle($globalConfig, $callerConfig, '<small>', '</small>').'</h3>');
$mainHeader = $item->renderMainHeaderFromString($globalConfig, $callerConfig, $mainHeader.$item->renderMainHeaders($globalConfig, $callerConfig, '&nbsp;&nbsp;'), '<div class="kt-portlet__head-label">', '</div>');
$toolContent = $item->renderTools($globalConfig, $callerConfig, '<div class="kt-portlet__head-toolbar panel_tools">', '</div>');
?>
<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'modal-content kt-portlet');?>
	<?php echo $item->renderHeaderFromString($globalConfig, $callerConfig, $mainHeader.$toolContent, '<div class="modal-header kt-portlet__head">', '</div>');?>
	<div class="modal-body kt-portlet__body">
		<?php echo $item->renderInnerContent($globalConfig, $callerConfig);?>
	</div>
	<?php echo $item->renderFooters($globalConfig, $callerConfig, '<div class="modal-footer kt-portlet__foot">', '</div>');?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>