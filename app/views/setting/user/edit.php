<?php $this->parent('/layout/page_settings') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="flex-col">
	<div class="flex body">
		<h1>
			<?php if (!$user->isNew()) { ?>
				<?= $user->name ?>
			<?php } else { ?>
				New <?= $info->singular ?>
			<?php } ?>
		</h1>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $user,
					'name'		=> 'isEnabled',
					'label'		=> 'Enabled',
					'disabled'	=> $user->isDeveloper() && !$req->user->isDeveloper(),
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $user,
					'name'		=> 'name',
					'label'		=> 'Name',
					'disabled'	=> $user->isDeveloper() && !$req->user->isDeveloper(),
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $user,
					'type'		=> 'input_email',
					'name'		=> 'emailAddress',
					'label'		=> 'Email Address',
					'disabled'	=> $user->isDeveloper() && !$req->user->isDeveloper(),
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $user,
					'name'		=> 'passwordPlain',
					'label'		=> 'Password',
					'type'		=> 'input_password',
					'disabled'	=> $user->isDeveloper() && !$req->user->isDeveloper(),
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $user,
					'name'		=> 'role',
					'label'		=> 'Role',
					'type'		=> 'input_radio',
					'disabled'	=> $user->isDeveloper() && !$req->user->isDeveloper(),
					'values'	=> $user->isDeveloper() || $req->user->isDeveloper() ? [
						\Chalk\Core\User::ROLE_CONTRIBUTOR		=> 'Contributor',
						\Chalk\Core\User::ROLE_EDITOR			=> 'Editor',
						\Chalk\Core\User::ROLE_ADMINISTRATOR	=> 'Administrator',
						\Chalk\Core\User::ROLE_DEVELOPER	    => 'Developer',
					] : [
						\Chalk\Core\User::ROLE_CONTRIBUTOR		=> 'Contributor',
						\Chalk\Core\User::ROLE_EDITOR			=> 'Editor',
						\Chalk\Core\User::ROLE_ADMINISTRATOR	=> 'Administrator',
					],
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok" <?= $user->isDeveloper() && !$req->user->isDeveloper() ? 'disabled' : null ?>>
					Save <?= $info->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<?php if (!$user->isNew() && (!$user->isDeveloper()  || $req->user->isDeveloper())) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete',
					]) ?>" class="btn btn-negative btn-quiet confirmable icon-delete" data-message="Are you sure?<?= "\n\n" ?>If you delete <?= $user->name ?> you will no longer be able to see which changes they made. If you just want to prevent this user from accessing the system you can disable the account by unchecking the Enabled box.">
						Delete <?= $info->singular ?>
					</a>
				</li>
			<?php } ?>
		</ul>
	</fieldset>
</form>