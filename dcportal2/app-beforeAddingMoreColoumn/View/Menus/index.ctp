<div class="menus index">
	<h2><?php __('Menus');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
                        <th>ID</th>
			<th>Menu</th>
                        <th>URL</th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($menus as $key=>$value):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
                if(strpos($value, '<img')===false){$value = '<img style="float:left;" src="'.$this->Html->url('/img/home.png') .'"><font size=+1><b><u>'.$value.'</u></b></font>';}
	?>
	<tr<?php echo $class;?>>
            <td><?php echo $key; ?>&nbsp;</td>
		<td align="left" style="text-align:left;"><?php echo $value; ?>&nbsp;</td>
                <td><?php echo $urls[$key]; ?></td>
		<td class="actions">
                    <?php echo $this->Html->link(__('Add Sub-Menu', true),array('action'=>'add', $key)); ?>
                    <?php echo $this->Html->link(__('Edit', true),array('action'=>'edit', $key)); ?>
                    <?php echo $this->Html->link(__('Up', true),array('action'=>'moveup', $key)); ?>
                    <?php echo $this->Html->link(__('Down', true), array('action'=>'movedown', $key)); ?>
                    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $key), null, sprintf(__('Are you sure you want to delete # %s?', true), $key)); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
        </div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Menu', true), array('action' => 'add')); ?></li>
	</ul>
</div>