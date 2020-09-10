<?php
/** @var \muuska\html\listing\tree\TreeItem $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php 
    $hasSubValues = $item->hasSubValues($globalConfig, $callerConfig);
    $hasActions = $item->hasActions($globalConfig, $callerConfig);
    $titleString = $item->renderFieldValueByName('title', $globalConfig, $callerConfig);
?>
<?php echo $item->drawStartTag('li', $globalConfig, $callerConfig, 'item'.$item->getStringFromCondition($hasSubValues, ' has_children'))?>
    <div class="accordion_header clearfix">
	<?php if($hasActions):?>
		<div class="texts specific_trigger">
			<?php if($item->needItemSelector($globalConfig, $callerConfig)):?>
            	<?php echo $item->getCheckboxOrRadioHtml($globalConfig, $callerConfig, '<label class="kt-checkbox">', $titleString.'<span></span></label>', '<label class="kt-radio">', $titleString.'<span></span></label>');?>
            <?php else:?>
            <?php echo $titleString;?>
            <?php endif;?>
        </div>
		<div class="actions">
			<div class="btn-group no_min_width">
        		<?php $separatedActions = $item->separateActions($globalConfig, $callerConfig, 1);?>
        		<?php echo $item->renderAction($separatedActions['default'][0], $globalConfig, $callerConfig, 'defaultAction', '', '', $item->createCallerConfig('btn btn-secondary'));?>
        		<?php if(!empty($separatedActions['others'])):?>
        			<button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"></button>
        			<ul class="dropdown-menu dropdown-menu-right" role="menu">
        				<?php foreach($separatedActions['others'] as $action):?>
        					<?php echo $item->renderAction($action, $globalConfig, $callerConfig, 'otherAction', '', '', $item->createCallerConfig('dropdown-item'));?>
        				<?php endforeach;?>
        			</ul>
        		<?php endif;?>
        	</div>
		</div>
	<?php else:?>
		<div class="specific_trigger">
        <?php if($item->needItemSelector($globalConfig, $callerConfig)):?>
        	<?php echo $item->getCheckboxOrRadioHtml($globalConfig, $callerConfig, '<label class="kt-checkbox">', $titleString.'<span></span></label>', '<label class="kt-radio">', $titleString.'<span></span></label>');?>
        <?php else:?>
        <?php echo $titleString;?>
        <?php endif;?>
        </div>
	<?php endif;?>
    </div>
	<?php echo $item->drawItems($globalConfig, $callerConfig, '<ul class="sub_tree accordion_tree msk_accordion has_icon has_specific_trigger accordion_body">', '</ul>')?>
<?php echo $item->drawEndTag('li', $globalConfig, $callerConfig)?>