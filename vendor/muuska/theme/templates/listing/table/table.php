<?php 
/** @var \muuska\html\listing\table\Table $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
/** @var \muuska\html\listing\table\Column $column */
?>
<?php echo $item->drawStartTag('table', $globalConfig, $callerConfig, 'table')?>
	<?php if(!$item->isHeaderDisabled()):?>
	<thead>
		<?php $columns = $item->getFields();?>
		<tr role="row" class="heading">
			<?php if($item->needItemSelector() && !$item->isEmpty() && !$item->isOnlyOneItemSelectable()):?>
				<th width="10px" class="cell_checkable"><label class="kt-checkbox"><input type="checkbox" class="checkable_checkbox check_all_switcher"<?php echo $item->getStringFromCondition($item->isAllDataSelected(), ' checked');?>/><span></span></label></th>
			<?php endif;?>
			<?php $columnCount = 0;?>
			<?php foreach($columns as $column):?>
				<th class="<?php if($column->hasSorts()):?>sortable_column<?php endif;?>">
					<?php echo $column->getLabel().$column->renderSorts($globalConfig, $callerConfig, '<span class="sort_links">', '</span>');?>
				</th>
				<?php $columnCount += 1;?>
			<?php endforeach;?>
			<?php if($item->needActionsBlock()):?>
				<th><?php echo $item->getActionText();?></th>
				<?php $columnCount += 1;?>
			<?php endif;?>
		</tr>
		<?php if($item->hasSearchFields()):?>
			<tr role="row" class="filter">
				<?php if($item->needItemSelector() && !$item->isEmpty()):?>
				<td></td>
				<?php endif;?>
				<?php foreach($columns as $column):?>
					<td><?php echo $column->renderSearch($globalConfig, $callerConfig);?></td>
				<?php endforeach;?>
				<?php echo $item->renderSearchActions($globalConfig, $callerConfig, '<td>', '</td>');?>
			</tr>
		<?php endif;?>
	</thead>
	<?php endif;?>
	<tbody>
		<?php if($item->isEmpty()):?>
			<?php echo $item->drawString($item->getEmptyText(), '<tr><td class="list-empty" colspan="'.$columnCount.'"><div class="list-empty-msg"><i class="fa fa-exclamation-triangle list-empty-icon"></i> ', '</div></td></tr>');?>
		<?php else:?>
			<?php echo $item->drawItems($globalConfig, $callerConfig);?>
		<?php endif;?>
	</tbody>
<?php echo $item->drawEndTag('table', $globalConfig, $callerConfig)?>