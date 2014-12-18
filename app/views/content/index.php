<?php $this->layout('/layout/page_content') ?>
<?php $this->block('main') ?>

<?php if ($info->class != 'Chalk\Core\Content') { ?>
	<?= $this->render("/{$info->local->path}/index", [], $info->module->class) ?>
	<?php return; ?>
<?php } ?>

<?php
$contents = $this->em($info)
	->all($index->toArray());
?>
<form action="<?= $this->url->route() ?>">
	<h1>Content</h1>
	<?= $this->render('filters', ['filter' => $index]) ?>
	<table class="multiselectable">
		<colgroup>
			<col class="col-select">
			<col class="col-name">
			<col class="col-date">
			<col class="col-badge">
		</colgroup>
		<thead>
			<tr>
				<th scope="col" class="col-select">
					<input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
				</th>
				<th scope="col" class="col-name">Content</th>
				<th scope="col" class="col-date">Modified</th>
				<th scope="col" class="col-badge">Status</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($contents as $content) { ?>
				<?= $this->render('row', ['content' => $content]) ?>
			<?php } ?>
		</tbody>
	</table>
<form>