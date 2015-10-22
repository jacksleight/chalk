<?php $this->outer('/layout/page_settings') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="flex-col">
	<div class="header">
		<h1>
			<?php if (!$structure->isNew()) { ?>
				<?= $structure->name ?>
			<?php } else { ?>
				New <?= $info->singular ?>
			<?php } ?>
		</h1>
	</div>
	<div class="flex body">
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $structure,
					'name'		=> 'name',
					'label'		=> 'Name',
					'autofocus'	=> true,
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $structure,
					'name'		=> 'path',
					'label'		=> 'URL Path Prefix',
					'autofocus'	=> true,
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok">
					Save <?= $info->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<?php if (!$structure->isNew()) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete',
					]) ?>" class="btn btn-negative btn-out confirmable icon-delete">
						Delete <?= $info->singular ?>
					</a>
				</li>
			<?php } ?>
		</ul>
	</fieldset>
</form>