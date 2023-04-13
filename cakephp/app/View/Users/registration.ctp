<?php $this->assign('title', 'Registration'); ?>

<h2 class="mb-4">Registration</h2>
<?php
echo $this->Form->create('User', array('class' => 'max-w-500'));
echo $this->Form->input('username', array(
	'placeholder' => 'Enter your username...',
	'autofocus' => true
));
echo $this->Form->input('email', array('placeholder' => 'Enter your email...'));
echo $this->Form->input('password', array('placeholder' => 'Enter your password...') );
echo $this->Form->input('password_confirm', array(
	'type' => 'password',
	'label' => 'Confirm password',
	'placeholder' => 'Confirm your password...'
));
echo $this->Form->end(array(
	'label' => 'Register',
	'div' => false,
	'class' => 'btn btn-dark'
));
?>
<p class="my-3">
	Already have an account?
	<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>">
		Login
	</a>
</p>
