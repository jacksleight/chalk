<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>
<?php
$filter = $this->em->wrap(new \Ayre\Filter())
	->graphFromArray($req->queryParams());
$entites = $this->em($entityType->class)
	->fetchAll($filter->toArray());
?>

<h1><?= $entityType->singular ?></h1>
<?= $this->render('filters', ['filter' => $filter]) ?>
<table>
	<colgroup>
		<col class="col-select">
		<col class="col-type">
		<col class="col-name">
		<col class="col-date">
		<col class="col-status">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-select"></th>
			<th scope="col" class="col-type">Type</th>
			<th scope="col" class="col-name">Name</th>
			<th scope="col" class="col-date">Modified</th>
			<th scope="col" class="col-status">Status</th>
		</tr>
	</thead>
	<tbody class="selectable">
		<? foreach ($entites as $entity) { ?>
			<tr>
				<td class="col-select">
					<input type="checkbox">
				</td>
				<td class="col-type">
					<?= \Ayre::type($entity)->singular ?>
				</td>
				<th class="col-name" scope="row">
					<?= $entity->name ?>
				</th>
				<td class="col-date">
					<?= $entity->modifyDate->diffForHumans() ?>
				</td>
				<td class="col-status">
					<span class="label label-status-<?= $entity->status ?>"><?= $entity->status ?></span>
				</td>	
			</tr>
		<? } ?>
	</tbody>
</table>