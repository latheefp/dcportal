<div class="hardwares index">
	<h2><?php __('Hardwares');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('platform');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort('system_board');?></th>
			<th><?php echo $this->Paginator->sort('iou');?></th>
			<th><?php echo $this->Paginator->sort('pci');?></th>
			<th><?php echo $this->Paginator->sort('memory');?></th>
			<th><?php echo $this->Paginator->sort('rack_unit');?></th>
			<th><?php echo $this->Paginator->sort('internal_disk');?></th>
			<th><?php echo $this->Paginator->sort('pci_on_iou');?></th>
			<th><?php echo $this->Paginator->sort('ldom');?></th>
			<th><?php echo $this->Paginator->sort('raid');?></th>
			<th><?php echo $this->Paginator->sort('storage');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($hardwares as $hardware):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $hardware['Hardware']['id']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['platform']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['type']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['system_board']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['iou']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['pci']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['memory']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['rack_unit']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['internal_disk']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['pci_on_iou']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['ldom']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['raid']; ?>&nbsp;</td>
		<td><?php echo $hardware['Hardware']['storage']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $hardware['Hardware']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $hardware['Hardware']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $hardware['Hardware']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $hardware['Hardware']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Hardware', true), array('action' => 'add')); ?></li>
	</ul>
</div>