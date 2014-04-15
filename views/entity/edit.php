<? $this->layout('/layouts/page_settings') ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<? if ($this->entity->isPersisted($entity->getObject())) { ?>
		<li>
			<a href="<?= $this->url([
				'action' => 'delete',
			]) ?>" class="btn btn-negative confirm">
				<i class="fa fa-trash-o"></i> Delete <?= $entityType->info->singular ?>
			</a>
		</li>
	<? } ?>
</ul>
<h1><?= $entityType->info->singular ?></h1>
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