<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<? if (!$entity->isNew() || !$entity->isMaster()) { ?>
		<li><a href="#" class="btn">
			<i class="fa fa-clock-o"></i>
			View <?= $entityType->singular ?> History
		</a></li>
	<? } ?>
</ul>
<h1>
	<? if (!$entity->isNew() || !$entity->isMaster()) { ?>
		<?= $entity->name ?>
	<? } else { ?>
		New <?= $entityType->singular ?>
	<? } ?>
</h1>
<ul class="meta">
	<li>
		<span class="label label-status-<?= $entity->status ?>"><?= $entity->status ?></span>
	</li>
	<li>
		<i class="fa fa-asterisk"></i>
		Version <em><?= $entity->version ?></em>
	</li>
	<? if (!$entity->isNew()) { ?>
		<li>
			<i class="fa fa-calendar"></i>
			Last updated <em><?= $entity->modifyDate->diffForHumans() ?></em> by <em><?= $entity->modifyUserName ?></em>
		</li>
	<? } ?>
</ul>

<form action="<?= $this->url->route() ?>" method="post">
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Details</h2>
		</div>
		<div class="form-items">
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $entity,
				'name'		=> 'name',
				'label'		=> 'Name',
				'autofocus'	=> true,
				'disabled'	=> $entity->isArchived(),
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'type'		=> 'select',
				'entity'	=> $entity,
				'name'		=> 'layout',
				'label'		=> 'Layout',
				'null'		=> 'Default',
				'values'	=> [],
				'disabled'	=> $entity->isArchived(),
			)) ?>
		</div>
	</fieldset>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Content</h2>
		</div>
		<div class="form-items">
			<?= $this->render('/elements/form-input', array(
				'entity'	=> $entity,
				'name'		=> 'content',
				'type'		=> 'content',
				'disabled'	=> $entity->isArchived(),
			)) ?>
		</div>
	</fieldset>
	<fieldset>
		<ul class="toolbar">
			<? if (!$entity->isArchived()) { ?>
				<li><button class="btn-focus">
					<i class="fa fa-check"></i>
					Save <?= $entityType->singular ?>
				</button></li>
			<? } else { ?>
				<li><a href="<?= $this->url([
					'action' => 'restore'
				]) ?>" class="btn btn-focus">
					<i class="fa fa-repeat"></i>
					Restore <?= $entityType->singular ?>
				</a></li>
			<? } ?>
		</ul>
		<ul class="toolbar">
			<? if (!$entity->isArchived()) { ?>
				<? if ((!$entity->isNew() || !$entity->isMaster())) { ?>
					<li><a href="<?= $this->url([
						'action' => 'status']) . $this->url->query([
						'status' => \Ayre::STATUS_ARCHIVED,
					]) ?>" class="btn btn-negative btn-quiet">
						<i class="fa fa-archive"></i>
						Archive <?= $entityType->singular ?>
					</a></li>
				<? } ?>
			<? } ?>
		</ul>
	</fieldset>
</form>