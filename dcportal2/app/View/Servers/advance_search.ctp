<?php
/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/
//echo $this->Form->create ('Advanc'(array ('type'=>'post', 'action'=>'servers/index' ));

echo $this->Html->charset();
echo $this->Html->css('search');
echo $this->Form->create('servers', array ('controller'=>'/','type'=>'get', 'action'=>'/index')); ?>
<table class='droptable'><tr><td>
<?php
echo $this->Form->input('location', array('type'=>'select', 'multiple'=>true, 'options'=> array(null => '--Location--', $location), array('selected'=>'--Location--')));
?>
</td>
<td>
  
    <?php
echo $this->Form->input('hostname', array('type'=>'select', 'multiple'=>true, 'options'=> array( null => '--Hosts--', $hosts), array('selected'=>'--Hosts--')));
?>
    </td><td>
<?php
echo $this->Form->input('os_type', array('type'=>'select', 'multiple'=>true, 'options'=> array(null => '--OS--', $os_type), array('selected'=>'--OS--')));
?>
        
        
</td></tr></table>
<?php
echo $this->Form->end(__('Submit', true));
?>



<!--<td class="option"> 
    <input type="radio" name="locationoption" value="AND" checked> AND <br> 
    <input type="radio" name="locationoption" value="OR" > OR <br>
</td>-->