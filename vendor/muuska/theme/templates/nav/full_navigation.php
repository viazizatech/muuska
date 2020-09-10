<?php
/** @var \muuska\html\nav\FullNavigation $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'row full_navigation');?>
	<div class="col-lg-2 kt-portlet">
		<?php echo $item->renderNav($globalConfig, $callerConfig);?>
	</div>
	<div class="col-lg-10 nav_contents">
		<?php echo $item->renderNavContents($globalConfig, $callerConfig);?>
	</div>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig);?>