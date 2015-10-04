<?php $this->outer('/layout/body_simple') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url([]) ?>" class="login" method="post">
	<fieldset>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $login,
			'name'		=> 'emailAddress',
			'label'		=> 'Email Address',
			'autofocus'	=> true,
		)) ?>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $login,
			'type'		=> 'input_password',
			'name'		=> 'password',
			'label'		=> 'Password',
			'autofocus'	=> true,
		)) ?>
		<?= $this->render('/element/form-input', array(
			'entity'	=> $login,
			'name'		=> 'referrer',
			'type'		=> 'input_hidden',
		)) ?>
		<button class="btn btn-block btn-focus">Login</button>
		<p class="login-password">
			<a href="<?= $this->url([], 'core_passwordRequest', true) ?>">Forgotten your password?</a>
		</p>
	</fieldset>
</form>
