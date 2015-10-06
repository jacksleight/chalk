<?php $this->outer('/layout/page_settings') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="flex-col">
	<div class="header">
		<h1>
			Merge Tags
		</h1>
	</div>
	<div class="flex body">
		<p>This will merge all items tagged with the source tag into the target tag, and then delete the source tag. <strong>This action cannot be undone.</strong></p>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>Tags</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $merge,
					'name'		=> 'sourceTag',
					'label'		=> 'Source',
					'null'		=> 'Select…',
					'values'	=> $tags = $this->em('core_tag')->all(),
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $merge,
					'name'		=> 'targetTag',
					'label'		=> 'Target',
					'null'		=> 'Select…',
					'values'	=> $tags,
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok confirmable">
					Merge <?= $info->plural ?>
				</button>
			</li>
		</ul>
	</fieldset>
</form>