<?php 
/** @var \muuska\html\listing\item\ListItem $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
/** @var \muuska\html\listing\table\Column $column */
?>
<?php echo $item->drawStartTag('tr', $globalConfig, $callerConfig)?>
<?php $columns = $item->getFinalFields($globalConfig, $callerConfig);?>
<?php if($item->needItemSelector($globalConfig, $callerConfig)):?>
	<td class="cell_checkable"><?php echo $item->getCheckboxOrRadioHtml($globalConfig, $callerConfig, '<label class="kt-checkbox">', '<span></span></label>', '<label class="kt-radio">', '<span></span></label>');?></td>
<?php endif;?>
<?php foreach($columns as $column):?>
	<?php echo $item->renderField($column, $globalConfig, 'td',  null);?>
<?php endforeach;?>
<?php if($item->needActionsBlock($globalConfig, $callerConfig)):?>
	<td>
		<?php if($item->hasActions($globalConfig, $callerConfig)):?>
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
		<?php endif;?>
	</td>
<?php endif;?>
<?php echo $item->drawEndTag('tr', $globalConfig, $callerConfig)?>