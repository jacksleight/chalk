<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>?mode=<?= $req->mode ?>&amp;post=1" method="post" data-modal-size="800x800">
	<div class="flex-col bottombar">
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
						Update <?= $info->singular ?>
					</button>
				</li>
			</ul>
			<ul class="toolbar">
				<?php if ($req->mode == 'edit') { ?>
					<li><a href="<?= $this->url([
						'action'	=> 'delete',
					]) ?>" class="btn btn-negative btn-out confirmable icon-delete">
						Delete <?= $info->singular ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</form>