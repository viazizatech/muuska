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