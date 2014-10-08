<?php $this->layout('/layouts/html') ?>
<?php $this->block('body') ?>

<div class="center"><div>
	<form action="" class="login" method="post">
		<fieldset>
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $login,
				'name'		=> 'emailAddress',
				'label'		=> 'Email Address',
				'autofocus'	=> true,
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $login,
				'type'		=> 'input_password',
				'name'		=> 'password',
				'label'		=> 'Password',
				'autofocus'	=> true,
			)) ?>
			<button class="btn btn-block btn-focus">Login</button>
		</fieldset>
	</form>
	<footer class="footer c" role="contentinfo">
		<p>Chalk <?= \Chalk\Chalk::VERSION ?></p>
	</footer>
</div></div>