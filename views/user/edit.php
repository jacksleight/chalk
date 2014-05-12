<? $this->layout('/layouts/page_settings') ?>
<? $this->block('main') ?>

<h1>
	<? if (!$user->isNew()) { ?>
		<?= $user->name ?>
	<? } else { ?>
		New <?= $entityType->singular ?>
	<? } ?>
</h1>
<form action="<?= $this->url->route() ?>" method="post">
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Details</h2>
		</div>
		<div class="form-items">
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $user,
				'name'		=> 'isEnabled',
				'label'		=> 'Enabled',
			)) ?>
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
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $user,
				'name'		=> 'role',
				'label'		=> 'Role',
				'type'		=> 'input_radio',
			)) ?>
		</div>
	</fieldset>
	<fieldset>
		<ul class="toolbar">
			<li>
				<button class="btn-focus">
					<i class="fa fa-check"></i>
					Save <?= $entityType->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<? if (!$user->isNew()) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete1',
					]) ?>" class="btn btn-negative btn-quiet confirmable" data-message="Are you sure?<?= "\n\n" ?>If you delete <?= $user->name ?> you will no longer be able to see which changes they made. If you just want to prevent this user from accessing the system you can disable the account by unchecking the Enabled box.">
						<i class="fa fa-trash-o"></i> Delete <?= $entityType->singular ?>
					</a>
				</li>
			<? } ?>
		</ul>
	</fieldset>
</form>