<?php
$struct = $this->em('Chalk\Core\Structure')->id($req->structure);
?>
<?php $this->parent('/layout/page_structure') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" class="col" data-modal-size="fullscreen" method="post">
	<div class="flex <?= $info->class == 'Chalk\Core\File' ? 'uploadable' : null ?>">
		<?php
		$contents = $this->em($info)
			->paged($index->toArray(), ['modifyDate', 'DESC'], $index->limit, $index->page);
		?>
		<?= $this->child("/{$info->local->path}/list", [
			'contents'		=> $contents,
			'isNewAllowed'	=> false,
			'isEditAllowed'	=> false,
			'headerPrefix'	=> 'Add',
		], $info->module->class) ?>
	</div>
	<div class="footer">
		<ul class="toolbar">
			<li>
				<button class="btn btn-positive icon-ok" formmethod="post">
					Add <?= $info->singular ?>
				</button>
			</li>
		</ul>
	</div>
<form>