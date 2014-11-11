<?php $this->layout('/layout/html') ?>
<?php $this->block('body') ?>

<div class="center"><div>
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
			<button class="btn btn-block btn-focus">Login</button>
			<p class="login-password">
				<a href="<?= $this->url([], 'passwordRequest', true) ?>">Forgotten your password?</a>
			</p>
		</fieldset>
	</form>
	<footer class="footer c" role="contentinfo">
		<p>Chalk <?= \Chalk\Chalk::VERSION ?></p>
	</footer>
</div></div>