<? $this->layout('/layouts/page_settings') ?>
<? $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<h1>
			Your Profile
		</h1>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $user,
					'name'		=> 'name',
					'label'		=> 'Name',
				)) ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $user,
					'type'		=> 'input_email',
					'name'		=> 'emailAddress',
					'label'		=> 'Email Address',
				)) ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $user,
					'name'		=> 'passwordPlain',
					'label'		=> 'Password',
					'type'		=> 'input_password',
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-focus">
					<i class="fa fa-check"></i>
					Save Profile
				</button>
			</li>
		</ul>
	</fieldset>
</form>