<?php 
/** @var \muuska\html\listing\pagination\Pagination $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
/** @var \muuska\translation\LangTranslator $translator */
?>

<?php $end = $item->getEndPage();?>
<?php $start = $item->getStartPage();?>
<?php echo $item->drawStartTag('ul', $globalConfig, $callerConfig, 'kt-pagination__links')?>
	<li class="kt-pagination__link--first<?php if(!$item->isFirstEnabled()):?> disabled<?php endif?>">
		<a href="<?php echo $item->getFirstUrl();?>" title="<?php echo $translator->l('First');?>"<?php echo $item->drawLinkClasses(null, true, true);?>><i class="fa fa-angle-double-left kt-font-info"></i></a>
	</li>
	<li class="kt-pagination__link--prev<?php if(!$item->isPrevEnabled()):?> disabled<?php endif?>">
		<a href="<?php echo $item->getPrevUrl();?>" title="<?php echo $translator->l('Prev');?>"<?php echo $item->drawLinkClasses(null, true, true);?>><i class="fa fa-angle-left kt-font-info"></i></a>
	</li>
	<?php for($page = $start; $page <= $end; $page++):?>
		<li<?php echo $item->drawActive($page, 'kt-pagination__link--active', true, true);?>>
			<a href="<?php echo $item->getPageUrl($page);?>" title="<?php echo $page;?>"<?php echo $item->drawLinkClasses(null, true, true);?>><?php echo $page;?></a>
		</li>
	<?php endfor;?>
	<li class="kt-pagination__link--next<?php if(!$item->isNextEnabled()):?> disabled<?php endif?>">
		<a href="<?php echo $item->getNextUrl();?>" title="<?php echo $translator->l('Next');?>"<?php echo $item->drawLinkClasses(null, true, true);?>><i class="fa fa-angle-right kt-font-info"></i></a>
	</li>
	<li class="kt-pagination__link--last<?php if(!$item->isLastEnabled()):?> disabled<?php endif?>">
		<a href="<?php echo $item->getLastUrl();?>" title="<?php echo $translator->l('Last');?>"<?php echo $item->drawLinkClasses(null, true, true);?>><i class="fa fa-angle-double-right kt-font-info"></i></a>
	</li>
<?php echo $item->drawEndTag('ul', $globalConfig, $callerConfig)?>