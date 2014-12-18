<?php if (!$req->isAjax()) { ?>
	<?php $this->layout('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>?mode=<?= $req->action ?>&amp;post=1" method="post" class="fill">
	<div class="flex">
		<ul class="toolbar">
			<li><span class="btn btn-quieter modal-close icon-cancel">
				Close
			</span></li>
		</ul>
		<h1>
			<?= $info->singular ?>
		</h1>
		<?= $this->render("{$info->local->path}", [], $info->module->class) ?>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-positive icon-ok">
					Update <?= $info->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<? if ($req->mode == 'edit') { ?>
				<li><a href="<?= $this->url([
					'action'	=> 'delete',
				]) ?>" class="btn btn-negative btn-quiet confirmable icon-delete" data-message="Are you sure?<?= "\n" ?>This will delete the <?= strtolower($info->singular) ?> and cannot be undone.">
					Delete <?= $info->singular ?>
				</a></li>
			<? } ?>
		</ul>
	</fieldset>
</form>