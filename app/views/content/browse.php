<?php if (!$req->isAjax()) { ?>
	<?php $this->parent('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>" class="flex-col" data-modal-size="fullscreen" method="post">
	<?= $this->render('/element/form-input', array(
		'type'		=> 'input_hidden',
		'entity'	=> $index,
		'name'		=> 'entity',
	)) ?>
	<div class="flex <?= $info->class == 'Chalk\Core\File' ? 'uploadable' : null ?>">
		<?php
		$contents = $this->em($info)
			->paged($index->toArray(), null, $index->limit, $index->page);
		?>
		<?= $this->child("/{$info->local->path}/list", [
			'contents'		=> $contents,
			'isNewAllowed'	=> false,
			'isEditAllowed'	=> false,
			'headerPrefix'	=> 'Browse',
		], $info->module->class) ?>
	</div>
	<div class="footer">
		<ul class="toolbar">
			<li>
				<button class="btn btn-positive icon-ok" formmethod="post">
					Select <?= $info->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<li><span class="btn modal-close icon-cancel">
				Close
			</span></li>
		</ul>
	</div>
<form>