<div class="jobs form">
<?php echo $this->Form->create('Job');?>
	<fieldset>
 		<legend><?php echo 'Add Job'; ?></legend>
	<?php
		echo $this->Form->input('name',array('label'=>'Job Name'));
                echo $this->Form->input('clients',array('options'=>$clients,'multiple'=>true));
		echo $this->Form->input('job_type',array('options'=>$Job_Type));
		echo $this->Form->input('job_data',array('div'=>'datadiv'));
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<div class="actions">
	<h3><?php 'Actions'; ?></h3>
	<ul>
		<li><?php echo $this->Html->link('List Jobs', array('action' => 'index'));?></li>
	</ul>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#JobJobType').change(function(){
            switch($('#JobJobType option:selected').text()){
                case 'pullexec':
                case 'push':
                case 'pull':
                $('.datadiv').show('fast');
                break;
                case 'exec':
                $('.datadiv').show('fast');
                break;

                default:
                    $('.datadiv').hide('fast');
                    break;
            }
        });
        $('#JobJobType').change();

    });
</script>