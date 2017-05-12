<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/html') ?>
	<?php $this->block('body') ?>
<?php } ?>

<?php
$structure = $this->em('Chalk\Core\Structure')->id($req->structure);
?>
<form action="<?= $this->url->route() ?>" class="flex-col bottombar" novalidate>
	<div class="header">
		<h1>Browse</h1>
		<button style="display: none;"></button>
	</div>
	<?= $this->render("/content/browser", [
		'index'	  => $index,
		'filters' => $filters,
	]) ?>
	<div class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok" formmethod="post">
					Select Content
				</button>
			</li>
		</ul>
	</div>
<form>