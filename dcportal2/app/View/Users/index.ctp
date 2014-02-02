

<div id="login">
	<?php echo $this->Form->create('User'); ?>
    	<p><b>Login enter your credentials</b></p>
        <p>
            <?php echo $this->Form->input('loginid', array( 'label' => 'User Name')); ?>
        </p>
        
        <p>
            <?php echo $this->Form->input('password', array( 'label' => 'Password')); ?>
        </p>
        <p>
        	<input type="submit" id="submit" value="Login" name="submit" />
        </p>
    <?php echo $this->Form->end(); ?>
</div><!--end login-->