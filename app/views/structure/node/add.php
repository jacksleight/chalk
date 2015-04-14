<?php if (!$req->isAjax()) { ?>
	<?php $this->parent('/layout/html') ?>
	<?php $this->block('body') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>" class="flex-col" data-modal-size="fullscreen">
	<?= $this->render("/content/browser", [
		'index'	=> $index,
	]) ?>
	<div class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-add" formmethod="post">
					Add <?= $info->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<li><span class="btn modal-close icon-cancel">
				Cancel
			</span></li>
		</ul>
	</div>
<form>