<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<? if ($this->entity->isPersisted($entity->getObject())) { ?>
		<li>
			<a href="<?= $this->url([
				'action' => 'archive',
			]) ?>" class="btn btn-negative btn-quiet">
				<i class="fa fa-archive"></i> Archive <?= $entityType->info->singular ?>
			</a>
		</li>
	<? } ?>
</ul>
<h1>
	<? if ($this->entity->isPersisted($entity->getObject())) { ?>
		<?= $entity->name ?>		
	<? } else { ?>
		New <?= $entityType->info->singular ?>
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
				'name'		=> 'name',
				'label'		=> 'Name',
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
			<li>
				<button>
					<i class="fa fa-check"></i>
					Save <?= $entityType->info->singular ?>
				</button>
			</li>
		</ul>
	</fieldset>
</form>