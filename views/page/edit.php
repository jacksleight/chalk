<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<li class="status">
		<? if (!$entity->isNew()) { ?>
			This is the
				<span class="label label-status-<?= $entity->status ?>"><?= $entity->status ?></span>
				version <strong><?= $this->locale->spellout($entity->version) ?></strong>
		<? } else { ?>
			This is a <strong>new</strong>
				<span class="label label-status-<?= $entity->status ?>"><?= $entity->status ?></span> version
			<? if (!$entity->isMaster()) { ?>
				based on the 
				<span class="label label-status-<?= $entity->previous->status ?>"><?= $entity->previous->status ?></span>
				copy
			<? } ?>
		<? } ?>
	</li>
	<? if (!$entity->isNew() || !$entity->isMaster()) { ?>
		<li class="space"><a href="#" class="btn">
			<i class="fa fa-clock-o"></i>
			View History
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
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'type'		=> 'select',
				'entity'	=> $entity,
				'name'		=> 'layout',
				'label'		=> 'Layout',
				'values'	=> [],
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
			)) ?>
		</div>
	</fieldset>
	<fieldset>
		<ul class="toolbar">
			<li><button class="btn-pending" name="status" value="<?= \Ayre::STATUS_PENDING ?>">
				<i class="fa fa-check"></i>
				<? if ($entity->status != \Ayre::STATUS_PENDING) { ?>Save as Pending<? } else { ?>Save Pending<? } ?>
			</button></li>
			<? if ($entity->status != \Ayre::STATUS_PENDING) { ?>
				<li><button class="btn-focus">
					<i class="fa fa-save"></i>
					Save <?= ucfirst($entity->status) ?>
				</button></li>
			<? } ?>
		</ul>
		<ul class="toolbar">
			<? if ($entity->status != \Ayre::STATUS_DRAFT) { ?>
				<li><button class="btn-negative btn-quiet" name="status" value="<?= \Ayre::STATUS_DRAFT ?>">
					<i class="fa fa-reply"></i>
					Save as Draft
				</button></li>
			<? } ?>
			<? if ($entity->status != \Ayre::STATUS_ARCHIVED) { ?>
				<li><button class="btn-negative btn-quiet" name="status" value="<?= \Ayre::STATUS_ARCHIVED ?>">
					<i class="fa fa-archive"></i>
					Save to Archive
				</button></li>
			<? } ?>
		</ul>
	</fieldset>
</form>