<?php
/** @var \muuska\html\listing\tree\TreeItem $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php 
    $hasSubValues = $item->hasSubValues($globalConfig, $callerConfig);
    $newAttributes = array('aria-haspopup'=>'true');
    $hasSubValuesClass = '';
    if ($hasSubValues) {
        $newAttributes['data-ktmenu-submenu-toggle'] = 'click';
        $hasSubValuesClass = ' kt-menu__item--submenu';
    }
    $defaultLink = $item->getDefaultLink($globalConfig, $callerConfig);
?>
<?php echo $item->drawStartTag('li', $globalConfig, $callerConfig, 'kt-menu__item'.$hasSubValuesClass, null, $newAttributes)?>
<?php echo $item->drawDefaultLinkStartTag($defaultLink, $globalConfig, null, 'kt-menu__link'.$item->getStringFromCondition($hasSubValues, 'kt-menu__toggle', '', true))?>
<?php echo $item->renderTitle($globalConfig, $callerConfig, 'span', null, 'kt-menu__link-text');?>
<?php echo $item->getStringFromCondition($hasSubValues, '<i class="kt-menu__ver-arrow la la-angle-right"></i>')?>
<?php echo $item->drawDefaultLinkEndTag($defaultLink, $globalConfig);?>
<?php echo $item->drawItems($globalConfig, $callerConfig, '<div class="kt-menu__submenu--classic kt-menu__submenu--left"><ul class="kt-menu__subnav">', '</ul></div>')?>
<?php echo $item->drawEndTag('li', $globalConfig, $callerConfig)?>