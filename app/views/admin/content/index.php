<?php $this->layout('/layouts/page_content') ?>
<?php $this->block('main') ?>

<?php if ($entity->name != 'core_content') { ?>
	<?= $this->render("/{$entity->local->path}/index", [], $entity->module->name) ?>
	<?php return; ?>
<?php } ?>

<?php
$contents = $this->em($entity)
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
			<col class="col-status">
		</colgroup>
		<thead>
			<tr>
				<th scope="col" class="col-select">
					<input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
				</th>
				<th scope="col" class="col-name">Content</th>
				<th scope="col" class="col-date">Modified</th>
				<th scope="col" class="col-status">Status</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($contents as $content) { ?>
				<?= $this->render('row', ['content' => $content]) ?>
			<?php } ?>
		</tbody>
	</table>
<form>