<?php 
/** @var \muuska\html\panel\ListPanel $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php
$mainHeader = $item->renderIcon($globalConfig, $callerConfig, '<span class="kt-portlet__head-icon">', '</span>');
$mainHeader .= $item->renderTitle($globalConfig, $callerConfig, '<h3 class="kt-portlet__head-title">', $item->renderSubTitle($globalConfig, $callerConfig, '<small>', '</small>').$item->renderTotalResult($globalConfig, $callerConfig, '<small>', '</small>').'</h3>');
$mainHeader .= $item->renderSelectedDataIndicator($globalConfig, $callerConfig, '&nbsp;&nbsp;').$item->renderBulkActionArea($globalConfig, $callerConfig, '&nbsp;&nbsp;').$item->renderQuickSearchArea($globalConfig, $callerConfig, '&nbsp;&nbsp;').$item->renderSortArea($globalConfig, $callerConfig, '&nbsp;&nbsp;');
$mainHeader = $item->renderMainHeaderFromString($globalConfig, $callerConfig, $mainHeader.$item->renderMainHeaders($globalConfig, $callerConfig, '&nbsp;&nbsp;'), '<div class="kt-portlet__head-label">', '</div>');
$toolContent = $item->renderTools($globalConfig, $callerConfig, '<div class="kt-portlet__head-toolbar panel_tools">', '</div>');
?>
<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'modal-content kt-portlet');?>
	<?php echo $item->renderHeaderFromString($globalConfig, $callerConfig, $mainHeader.$toolContent, '<div class="modal-header kt-portlet__head">', '</div>');?>
	<div class="modal-body kt-portlet__body">
		<?php echo $item->renderSpecificSearchArea($globalConfig, $callerConfig);?>
		<?php echo $item->renderInnerContent($globalConfig, $callerConfig);?>
		<?php if(($item->getComponentName() === 'list_panel') && ($item->hasPagination() || $item->hasLimiterSwitcher())):?>
		<div class="pagination_info kt-pagination kt-pagination--info">
			<?php echo $item->renderPagination($globalConfig, $callerConfig);?>
			<div class="kt-pagination__toolbar">
				<?php echo $item->renderLimiterSwitcher($globalConfig, $callerConfig);?>
				<?php echo $item->renderPaginationDescription($globalConfig, $callerConfig, '<span class="pagination__desc">', '</span>');?>
			</div>
		</div>
		<?php endif;?>
	</div>
	<?php echo $item->renderFooters($globalConfig, $callerConfig, '<div class="modal-footer kt-portlet__foot">', '</div>');?>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>