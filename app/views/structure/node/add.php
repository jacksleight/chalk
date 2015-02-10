<?php if (!$req->isAjax()) { ?>
	<?php $this->parent('/layout/html') ?>
	<?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>" class="flex-col" data-modal-size="fullscreen" method="post">
	<div class="flex flex-row <?= $info->class == 'Chalk\Core\File' ? 'uploadable' : null ?>">
		<div class="sidebar">
			
		</div>
		<div class="flex">
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
	</div>
	<div class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok" formmethod="post">
					Add <?= $info->singular ?>
				</button>
			</li>
		</ul>
	</div>
<form>