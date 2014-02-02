<div class="menus form">
<?php echo $this->Form->create('Menu');?>
	<fieldset>
 		<legend><?php __('Edit Menu'); ?></legend>
	<?php
		echo $this->Form->input('id'); //echo $form->hidden('id');
		echo $this->Form->input('name');
		echo $this->Form->input('url',array('label'=>'Link'));
		echo $this->Form->input('parent_id'); //, array('selected'=>$this->data['Menu']['parent_id'])
	?>
	</fieldset>
<?php echo $this->Form->end(__('Update', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Menus', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('New Menu', true), array('controller' => 'menus', 'action' => 'add')); ?> </li>
                <li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Menu.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Menu.id'))); ?></li>

	</ul>
</div>
