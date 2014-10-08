<?php $this->layout('/layouts/page_settings') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<h1>
			<?php if (!$user->isNew()) { ?>
				<?= $user->name ?>
			<?php } else { ?>
				New <?= $entity->singular ?>
			<?php } ?>
		</h1>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $user,
					'name'		=> 'isEnabled',
					'label'		=> 'Enabled',
					'disabled'	=> $user->isRoot(),
				)) ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $user,
					'name'		=> 'name',
					'label'		=> 'Name',
					'disabled'	=> $user->isRoot(),
				)) ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $user,
					'type'		=> 'input_email',
					'name'		=> 'emailAddress',
					'label'		=> 'Email Address',
					'disabled'	=> $user->isRoot(),
				)) ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $user,
					'name'		=> 'passwordPlain',
					'label'		=> 'Password',
					'type'		=> 'input_password',
					'disabled'	=> $user->isRoot(),
				)) ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $user,
					'name'		=> 'role',
					'label'		=> 'Role',
					'type'		=> 'input_radio',
					'disabled'	=> $user->isRoot(),
					'values'	=> $user->isRoot() ? [
						\Chalk\Core\User::ROLE_CONTRIBUTOR	=> 'Contributor',
						\Chalk\Core\User::ROLE_EDITOR		=> 'Editor',
						\Chalk\Core\User::ROLE_ROOT			=> 'Administrator',
					] : null,
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-focus" <?= $user->isRoot() ? 'disabled' : null ?>>
					<i class="fa fa-check"></i>
					Save <?= $entity->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<?php if (!$user->isNew() && !$user->isRoot()) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete1',
					]) ?>" class="btn btn-negative btn-quiet confirmable" data-message="Are you sure?<?= "\n\n" ?>If you delete <?= $user->name ?> you will no longer be able to see which changes they made. If you just want to prevent this user from accessing the system you can disable the account by unchecking the Enabled box.">
						<i class="fa fa-trash-o"></i> Delete <?= $entity->singular ?>
					</a>
				</li>
			<?php } ?>
		</ul>
	</fieldset>
</form>