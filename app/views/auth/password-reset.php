<?php $this->layout('/layout/html') ?>
<?php $this->block('body') ?>

<div class="center"><div>
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
	<footer class="footer c" role="contentinfo">
		<p>Chalk <?= \Chalk\Chalk::VERSION ?></p>
	</footer>
</div></div>