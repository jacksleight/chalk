<?php $this->layout('/layout/body_simple') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url([]) ?>" class="login" method="post">
	<fieldset>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $passwordReset,
			'name'		=> 'password',
			'type'		=> 'input_password',
			'label'		=> 'New Password',
			'autofocus'	=> true,
		)) ?>
		<button class="btn btn-block btn-focus">Reset Password</button>
	</fieldset>
</form>
