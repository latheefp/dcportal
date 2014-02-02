<div class="jobs index">
	<h2>Jobs</h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('job_type');?></th>
			<th><?php echo $this->Paginator->sort('status');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th class="actions">Actions</th>
	</tr>
	<?php
	$i = 0;
        
	foreach ($jobs as $job):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}

	?>
	<tr<?php echo $class;?>>
		<td><?php echo $job['Job']['id']; ?>&nbsp;</td>
		<td><?php echo $job['Job']['name']; ?>&nbsp;</td>
		<td><?php echo $Job_Type[$job['Job']['job_type']]; ?>&nbsp;</td>
		<td><?php echo $Job_Status[$job['Job']['status']]; ?>&nbsp;</td>
		<td><?php echo $job['Job']['created']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($job['User']['name'], array('controller' => 'users', 'action' => 'view', $job['User']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link('View', array('action' => 'view', $job['Job']['id'])); ?>
			<?php 
                        if($Job_Status[$job['Job']['status']] != 'Completed'){
                            if($Job_Status[$job['Job']['status']] != 'Suspended'){
                                echo $this->Html->link('Suspend', array('action' => 'suspend', $job['Job']['id']), null, sprintf('Are you sure you want to suspend # %s?', $job['Job']['id']));
                            }else{
                                echo $this->Html->link('Unsuspend', array('action' => 'unsuspend', $job['Job']['id']), null, sprintf('Are you sure you want to UNsuspend # %s?', $job['Job']['id']));
                            }
                        }
                        ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'
	));
	?></p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . 'previous', array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next('next' . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3>Actions</h3>
	<ul>
		<li><?php echo $this->Html->link('New Job', array('action' => 'AddJob')); ?></li>
                <li><?php echo $this->Html->link('Delete old jobs', array('action' => 'deleteoldjobs')); ?></li>
	</ul>
</div>