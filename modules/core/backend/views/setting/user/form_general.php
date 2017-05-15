<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'name'		=> 'isEnabled',
	'label'		=> 'Enabled',
	'disabled'	=> $entity->isDeveloper() && !$req->user->isDeveloper(),
), 'core') ?>
<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'name'		=> 'name',
	'label'		=> 'Name',
	'disabled'	=> $entity->isDeveloper() && !$req->user->isDeveloper(),
	'autofocus'	=> true,
), 'core') ?>
<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'type'		=> 'input_email',
	'name'		=> 'emailAddress',
	'label'		=> 'Email Address',
	'disabled'	=> $entity->isDeveloper() && !$req->user->isDeveloper(),
), 'core') ?>
<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'name'		=> 'passwordPlain',
	'label'		=> 'Password',
	'type'		=> 'input_password',
	'disabled'	=> $entity->isDeveloper() && !$req->user->isDeveloper(),
), 'core') ?>
<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'name'		=> 'role',
	'label'		=> 'Role',
	'type'		=> 'input_radio',
	'disabled'	=> $entity->isDeveloper() && !$req->user->isDeveloper(),
	'values'	=> $entity->isDeveloper() || $req->user->isDeveloper() ? [
		\Chalk\Core\User::ROLE_CONTRIBUTOR		=> 'Contributor',
		\Chalk\Core\User::ROLE_EDITOR			=> 'Editor',
		\Chalk\Core\User::ROLE_ADMINISTRATOR	=> 'Administrator',
		\Chalk\Core\User::ROLE_DEVELOPER	    => 'Developer',
	] : [
		\Chalk\Core\User::ROLE_CONTRIBUTOR		=> 'Contributor',
		\Chalk\Core\User::ROLE_EDITOR			=> 'Editor',
		\Chalk\Core\User::ROLE_ADMINISTRATOR	=> 'Administrator',
	],
), 'core') ?>