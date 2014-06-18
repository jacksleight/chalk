<? $this->layout('/layouts/page_settings') ?>
<? $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<h1>
			<? if (!$structure->isNew()) { ?>
				<?= $structure->name ?>
			<? } else { ?>
				New <?= $entity->singular ?>
			<? } ?>
		</h1>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $structure,
					'name'		=> 'name',
					'label'		=> 'Name',
					'autofocus'	=> true,
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-focus">
					<i class="fa fa-check"></i>
					Save <?= $entity->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<? if (!$structure->isNew()) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete',
					]) ?>" class="btn btn-negative btn-quiet confirmable">
						<i class="fa fa-trash-o"></i> Delete <?= $entity->singular ?>
					</a>
				</li>
			<? } ?>
		</ul>
	</fieldset>
</form>