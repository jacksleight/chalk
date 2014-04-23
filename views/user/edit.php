<? $this->layout('/layouts/page_settings') ?>
<? $this->block('main') ?>

<h1>
	<? if (!$entity->isNew() || !$entity->isMaster()) { ?>
		<?= $entity->name ?>
	<? } else { ?>
		New <?= $entityType->singular ?>
	<? } ?>
</h1>
<form action="<?= $this->url->route() ?>" method="post" novalidate>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Details</h2>
		</div>
		<div class="form-items">
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $entity,
				'name'		=> 'isEnabled',
				'label'		=> 'Enabled',
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $entity,
				'name'		=> 'name',
				'label'		=> 'Name',
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $entity,
				'name'		=> 'emailAddress',
				'label'		=> 'Email Address',
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $entity,
				'name'		=> 'passwordPlain',
				'label'		=> 'Password',
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $entity,
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
			<? if (isset($entity->id)) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete1',
					]) ?>" class="btn btn-negative btn-quiet confirm" data-message="Are you sure?<?= "\n\n" ?>If you delete <?= $entity->name ?> you will no longer be able to see which changes they made. If you just want to prevent this user from accessing the system you can disable the account by unchecking the Enabled box.">
						<i class="fa fa-trash-o"></i> Delete <?= $entityType->singular ?>
					</a>
				</li>
			<? } ?>
		</ul>
	</fieldset>
</form>