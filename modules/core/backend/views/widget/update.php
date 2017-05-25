<?php $this->outer('layout/page', [
    'title' => $info->singular,
], 'core') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url([]) . $this->url->query([
	'method' => 'post',
]) ?>" method="post" data-modal-size="800x800" class="flex-col">
	<div class="header">
		<h1>
			<?= $info->singular ?>
		</h1>
	</div>
	<div class="body flex">
		<?php 
		$module   = $this->app->chalk->module($info->module->name);
		$editView = $module->widgetEditView($widget->getObject());
		?>
		<?= is_array($editView)
			? $this->inner($editView[0], [], $editView[1])
			: $this->inner($editView) ?>
	</div>
	<div class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok">
					Update
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<?php if (!$widget->isNew()) { ?>
				<li><a href="<?= $this->url([]) . $this->url->query([
					'method' => 'delete',
				]) ?>" class="btn btn-negative btn-out confirmable icon-delete">
					Delete
				</a></li>
			<?php } ?>
		</ul>
	</div>
</form>