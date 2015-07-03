<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/html') ?>
	<?php $this->block('body') ?>
<?php } ?>

<?php
$structure = $this->em('Chalk\Core\Structure')->id($req->structure);
?>
<form action="<?= $this->url->route() ?>" class="flex-col" data-modal-size="fullscreen">
	<?= $this->render("/content/browser", [
		'index'		=> $index,
		'restricts'	=> 'node',
	]) ?>
	<div class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-add" formmethod="post">
					Add to <?= $structure->name ?>
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