<?php
//echo $html->script('jquery.maskedinput.min');
//echo $html->script('jquery.MetaData');
//echo $html->script('jquery.form');
//echo $html->script('jquery.MultiFile.pack');
//echo $html->script('jquery.watermarkinput.js');
?>

<div class="hardwares form">
<?php echo $this->Form->create('Hardware');?>
	<fieldset>
 		<legend><?php __('Add Hardware'); ?></legend>
	<?php
		echo $this->Form->input('platform');
		echo $this->Form->input('type');
		echo $this->Form->input('system_board');
		echo $this->Form->input('iou');
		echo $this->Form->input('pci');
		echo $this->Form->input('memory');
		echo $this->Form->input('rack_unit');
		echo $this->Form->input('internal_disk');
		echo $this->Form->input('pci_on_iou');
		echo $this->Form->input('ldom');
		echo $this->Form->input('raid');
		echo $this->Form->input('storage');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Hardwares', true), array('action' => 'index'));?></li>
	</ul>
</div>