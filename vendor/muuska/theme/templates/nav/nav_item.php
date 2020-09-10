<?php 
/** @var \muuska\html\nav\HtmlNavItem $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>

<?php echo $item->drawStartTag('li', $globalConfig, $callerConfig, 'nav_item kt-nav__item', null, null, array('href', 'title'));?>
<?php $iconCallerConfig = $item->createCallerConfig('kt-nav__link-icon');?>
	<a href="<?php echo $item->getHref();?>" class="kt-nav__link"<?php echo $item->drawString($item->getTitle(), 'title="', '"');?>><?php echo $item->renderIcon($globalConfig, $callerConfig, '', '', $iconCallerConfig).$item->renderInnerContent($globalConfig, $callerConfig, '<span class="kt-nav__link-text">', '</span>').$item->renderBadge($globalConfig, $callerConfig, '<span class="kt-nav__link-badge">', '</span>');?></a>
	<?php echo $item->renderSubItems($globalConfig, $callerConfig, '<ul class="kt-nav__sub">', '</ul>');?>
<?php echo $item->drawEndTag('li', $globalConfig, $callerConfig);?>