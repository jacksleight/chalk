<?php if (!$req->isAjax()) { ?>
	<?php $this->layout('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<ul class="toolbar">
			<li><span class="btn btn-quieter modal-close">
				<i class="fa fa-times"></i>
				Close
			</span></li>
		</ul>
		<h1>
			<?= $entity->singular ?>
		</h1>
		<?= $this->render("{$entity->local->path}", [], $entity->module->class) ?>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-focus">
					<i class="fa fa-check"></i>
					Update <?= $entity->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<li><a href="<?= $this->url([
				'action'	=> 'delete',
			]) ?>" class="btn btn-negative btn-quiet confirmable" data-message="Are you sure?<?= "\n" ?>This will delete the <?= strtolower($entity->singular) ?> and cannot be undone.">
				<i class="fa fa-trash-o"></i>
				Delete <?= $entity->singular ?>
			</a></li>
		</ul>
	</fieldset>
</form>