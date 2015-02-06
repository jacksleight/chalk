<?php $this->parent('/layout/page_settings') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="col">
	<div class="flex">
		<h1>
			<?php if (!$domain->isNew()) { ?>
				<?= $domain->name ?>
			<?php } else { ?>
				New <?= $info->singular ?>
			<?php } ?>
		</h1>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'name',
					'label'		=> 'Name',
					'autofocus'	=> true,
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $domain,
					'type'		=> 'select',
					'null'		=> 'Select…',
					'name'		=> 'structure',
					'label'		=> 'Structure',
					'values'    => $this->em('Chalk\Core\Structure')->all(),
				)) ?>
			</div>
		</fieldset>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>HTML</h2>
				<p>This will be inserted at the end of the <code>&lt;head&gt;</code> and <code>&lt;body&gt;</code> elements of every page.</p>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'head',
					'class'		=> 'monospaced editor-code',
					'rows'		=> 10,
					'label'		=> 'Head',
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'body',
					'class'		=> 'monospaced editor-code',
					'rows'		=> 10,
					'label'		=> 'Body',
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="footer">
		<ul class="toolbar">
			<li>
				<button class="btn btn-positive icon-ok">
					Save <?= $info->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<?php if (!$domain->isNew()) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete',
					]) ?>" class="btn btn-negative btn-quiet confirmable icon-delete">
						Delete <?= $info->singular ?>
					</a>
				</li>
			<?php } ?>
		</ul>
	</fieldset>
</form>