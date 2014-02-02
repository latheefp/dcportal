<div class="menus form">
    <?php echo $this->Form->create('Menu');?>
    <fieldset>
        <legend><?php __('Add Menu'); ?></legend>
        <?php
        echo $this->Form->input('name');
        echo $this->Form->input('url',array('label'=>'Link'));
        echo $form->input('parent_id',array('label'=>'Parent','selected'=>$selected_id));

        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Html->link(__('List Menus', true), array('action' => 'index'));?></li>
        <li><?php echo $this->Html->link(__('New Menu', true), array('controller' => 'menus', 'action' => 'add')); ?> </li>
    </ul>
</div>