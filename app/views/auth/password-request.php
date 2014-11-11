<?php $this->layout('/layout/html') ?>
<?php $this->block('body') ?>

<div class="center"><div>
	<form action="<?= $this->url([]) ?>" class="login" method="post">
		<fieldset>
			<?= $this->render('/element/form-item', array(
				'entity'	=> $passwordRequest,
				'name'		=> 'emailAddress',
				'label'		=> 'Email Address',
				'autofocus'	=> true,
			)) ?>
			<button class="btn btn-block btn-focus">Send Password Reset Request</button>
		</fieldset>
	</form>
	<footer class="footer c" role="contentinfo">
		<p>Chalk <?= \Chalk\Chalk::VERSION ?></p>
	</footer>
</div></div>