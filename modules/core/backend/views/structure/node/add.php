<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/html') ?>
	<?php $this->block('body') ?>
<?php } ?>

<?php
$structure = $this->em('Chalk\Core\Structure')->id($req->structure);
?>
<form action="<?= $this->url->route() ?>" class="flex-col">
	<div class="header">
		<ul class="toolbar toolbar-right">
	        <li class="toolbar-gap"><span class="btn btn-out btn-lighter modal-close icon-cancel">
	            Close
	        </span></li>
		</ul>
		<h1>Browse</h1>
	</div>
	<?= $this->render("/content/browser", [
		'index'		=> $index,
		'filters'	=> 'core_node',
	]) ?>
	<div class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-add" formmethod="post">
					Add to <?= $structure->name ?>
				</button>
			</li>
		</ul>
	</div>
<form>