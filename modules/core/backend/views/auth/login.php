<?php $this->outer('/layout/body_simple') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url([]) ?>?redirect=<?= $req->redirect ?>" class="login" method="post">
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
		)) ?>
		<button class="btn btn-block btn-focus">Login</button>
		<p class="login-password">
			<a href="<?= $this->url([], 'core_passwordRequest', true) ?>">Forgotten your password?</a>
		</p>
	</fieldset>
</form>
