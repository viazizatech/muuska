<?php
/** @var \muuska\html\HtmlCustomElement $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
/** @var \muuska\translation\LangTranslator $translator */
/** @var \muuska\renderer\template\Template $this */
?>
<?php echo $item->drawStartTag('div', $globalConfig, $callerConfig,
'home_wrapper')?>
<?php echo $item->drawContentByName('banner', $globalConfig,
$callerConfig);?>
<div class="kt-container">
<?php echo $item->drawContentByName('picto', $globalConfig,
$callerConfig);?>
<section class="page_section categories">
<p class="title"><?php echo $translator->l('Choose from our
categories');?></p>
<?php echo $item->drawContentByName('categories', $globalConfig,
$callerConfig);?>
</section>
<section class="page_section top_books">
<p class="title"><?php echo $translator->l('Top books');?></p>
<?php echo $item->drawContentByName('top_books', $globalConfig,
$callerConfig);?>
</section>
<section class="page_section faqs">
<p class="title"><?php echo $translator->l('Frequently asked
Questions');?></p>
<?php echo $item->drawContentByName('faqs', $globalConfig,
$callerConfig);?>
</section>
<?php echo $item->drawContentByName('partners', $globalConfig,
$callerConfig, '<section class="page_section partners"><p
class="title">'.$translator->l('Partners').'</p>', '</section>');?>
</div>
<?php echo $item->drawEndTag('div', $globalConfig, $callerConfig)?>