<?php $this->outer('/layout/body_simple') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url([]) ?>" class="login" method="post">
	<fieldset>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $model,
			'name'		=> 'emailAddress',
			'label'		=> 'Email Address',
			'autofocus'	=> true,
		)) ?>
		<button class="btn btn-block btn-focus">Send Password Reset Request</button>
	</fieldset>
</form>