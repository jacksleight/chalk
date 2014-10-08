<?php $this->layout('/layouts/page_settings') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<h1>
			<?php if (!$domain->isNew()) { ?>
				<?= $domain->name ?>
			<?php } else { ?>
				New <?= $entity->singular ?>
			<?php } ?>
		</h1>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'name',
					'label'		=> 'Name',
					'autofocus'	=> true,
				)) ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $domain,
					'type'		=> 'select_objects',
					'name'		=> 'structure',
					'label'		=> 'Structure',
					'values'    => $this->em('Chalk\Core\Structure')->fetchAll(),
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
			<?php if (!$domain->isNew()) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete',
					]) ?>" class="btn btn-negative btn-quiet confirmable">
						<i class="fa fa-trash-o"></i> Delete <?= $entity->singular ?>
					</a>
				</li>
			<?php } ?>
		</ul>
	</fieldset>
</form>