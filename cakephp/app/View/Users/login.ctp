<?php $this->assign('title', 'Login'); ?>

<h2 class="mb-2">Login</h2>
<div class="mb-2">
	Admin:
	<pre>Email: admin@pm.me<br>Password: admin</pre>
	User:
	<pre>Email: user@pm.me<br>Password: user</pre>
</div>
<?php
echo $this->Form->create('User', array('class' => 'max-w-500'));
echo $this->Form->input('email', array('placeholder' => 'Enter your email...', 'autofocus' => true));
echo $this->Form->input('password', array('placeholder' => 'Enter your password...') );
echo $this->Form->end(array(
	'label' => 'Login',
	'div' => false,
	'class' => 'btn btn-dark'
));
?>
<p class="my-3">
	Don't have an account?
	<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'registration')); ?>">
		Register
	</a>
</p>
