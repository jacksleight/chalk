<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<? if (isset($entity->id)) { ?>
		<li>
			<a href="<?= $this->url([
				'action' => 'archive',
			]) ?>" class="btn btn-negative">
				<i class="fa fa-archive"></i> Archive <?= $entityType->singular ?>
			</a>
		</li>
	<? } ?>
</ul>
<h1><?= $entityType->singular ?></h1>
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
					Save <?= $entityType->singular ?>
				</button>
			</li>
		</ul>
	</fieldset>
</form>