<?php $this->outer('/layout/page_wide') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="flex-col">
	<div class="header">
		<h1>
			Merge Tags
		</h1>
		<small>Find all items using the source tag, assign them the target tag, and then delete the source tag.</small>
	</div>
	<div class="flex body">
		<fieldset class="form-block">
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $model,
					'name'		=> 'sourceTag',
					'label'		=> 'Source',
					'null'		=> 'Select…',
					'values'	=> $tags = $this->em('core_tag')->all(),
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $model,
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
				<button class="btn btn-negative icon-ok confirmable">
					Merge <?= $info->plural ?>
				</button>
			</li>
		</ul>
	</fieldset>
</form>