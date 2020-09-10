<?php
/** @var \muuska\html\listing\tree\HtmlTree $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig, 'kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default')?>
<ul class="kt-menu__nav">
<?php echo $item->drawItems($globalConfig, $callerConfig);?>
</ul>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig)?>