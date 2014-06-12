<? if (!$req->isAjax()) { ?>
	<? $this->layout('/layouts/page_content') ?>
	<? $this->block('main') ?>
<? } ?>

<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<h1>
			<?= $entity->singular ?>
		</h1>
		<?= $this->render($entity->entity->path, [], $entity->module->name) ?>
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
			<li><span class="btn modal-close">
				<i class="fa fa-times"></i>
				Close
			</span></li>
		</ul>
	</fieldset>
</form>