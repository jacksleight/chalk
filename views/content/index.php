<? $this->layout('/layouts/page_content') ?>
<? $this->block('main') ?>

<? if ($entity->name != 'core_content') { ?>
	<?= $this->render("/{$entity->local->path}/index", [], $entity->module->name) ?>
	<?php return; ?>
<? } ?>

<?php
$contents = $this->em($entity->class)
	->fetchAll($index->toArray());
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
			<? foreach ($contents as $content) { ?>
				<?= $this->render('row', ['content' => $content]) ?>
			<? } ?>
		</tbody>
	</table>
<form>