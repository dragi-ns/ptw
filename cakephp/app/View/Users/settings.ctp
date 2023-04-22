<?php
/**
 * @var User $user
 */

$this->assign('title', h($user['User']['username']) . "'s settings");
?>

<section>
	<h2 class="mb-4">Profile Settings</h2>
	<?php
	echo $this->Form->create('User', array('class' => 'd-flex flex-column max-w-500'));
	echo $this->Form->input('username', array(
		'placeholder' => 'Enter your username...',
		'autofocus' => true
	));
	echo $this->Form->input('email', array('placeholder' => 'Enter your email...'));

	echo $this->Form->input('current_password', array(
		'type' => 'password',
		'placeholder' => 'Enter your current password...'
	));

	echo $this->Form->input('new_password', array(
		'type' => 'password',
		'placeholder' => 'Enter your password...'
	) );
	echo $this->Form->input('new_password_confirm', array(
		'type' => 'password',
		'label' => 'Confirm password',
		'placeholder' => 'Confirm your password...',
		'required' => false
	));
	echo $this->Form->input('id', ['type' => 'hidden']);
	echo $this->Form->end(array(
		'label' => 'Edit Account',
		'div' => false,
		'class' => 'btn btn-dark align-self-start'
	));
	?>
</section>

<section class="my-5">
	<h2 class="mb-4">Delete An Account</h2>
	<?php
	echo $this->Form->postButton(
		'<span class="icon"><i class="bi bi-trash-fill"></i></span><span class="d-none d-md-inline">Delete Account</span>',
		array(
			'controller' => 'users',
			'action' => 'delete',
			'admin' => false
		),
		array(
			'escape' => false,
			'class' => 'btn btn-danger',
			'onclick' => "return confirm('Are you sure you want to delete an account?');"
		)
	);
	?>
</section>
