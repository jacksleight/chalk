<? $this->layout('/layouts/page', [
	'class' => 'upload',
]) ?>
<? $this->block('main') ?>
<?php
$filter = $this->entity->wrap(new \Ayre\Filter())
	->graphFromArray($req->queryParams());
$entites = $this->entity($entityType->class)
	->fetchAll($filter->toArray());
?>

<ul class="toolbar">
	<li>
		<a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="button">
			<i class="fa fa-plus"></i> Add <?= $entityType->info->singular ?>
		</a>
	</li>
</ul>
<h1><?= $entityType->info->plural ?></h1>
<?= $this->render('filters', ['filter' => $filter]) ?>
<table>
	<colgroup>
		<col class="col-name">
		<col class="col-create">
		<col class="col-status">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-name">Name</th>
			<th scope="col" class="col-create">Added</th>
			<th scope="col" class="col-status">Status</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($entites as $entity) { ?>
			<?= $this->render('row', ['content' => $entity]) ?>
		<? } ?>
	</tbody>
</table>