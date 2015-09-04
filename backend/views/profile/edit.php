<?php $this->outer('/layout/page') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="flex-col">
	<div class="flex body">
		<h1>
			Your Profile
		</h1>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $user,
					'name'		=> 'name',
					'label'		=> 'Name',
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $user,
					'type'		=> 'input_email',
					'name'		=> 'emailAddress',
					'label'		=> 'Email Address',
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $user,
					'name'		=> 'passwordPlain',
					'label'		=> 'Password',
					'type'		=> 'input_password',
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok">
					Save Profile
				</button>
			</li>
		</ul>
	</fieldset>
</form>