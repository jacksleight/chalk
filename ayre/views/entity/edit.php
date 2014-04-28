<? $this->layout('/layouts/page_settings') ?>
<? $this->block('main') ?>

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
						'action' => 'delete',
					]) ?>" class="btn btn-negative btn-quiet confirmable">
						<i class="fa fa-trash-o"></i> Delete <?= $entityType->singular ?>
					</a>
				</li>
			<? } ?>
		</ul>
	</fieldset>
</form>