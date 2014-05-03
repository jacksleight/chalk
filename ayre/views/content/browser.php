<?php
$entites = $this->em($entityType->class)
	->fetchAll($index->toArray());
?>
<form action="<?= $this->url->route() ?>">
	<ul class="toolbar">
		<li><button class="btn btn-focus" formmethod="post">Select</button></li>
	</ul>
	<h1><?= $entityType->singular ?></h1>
	<?= $this->render('filters', ['filter' => $index]) ?>
	<table class="multiselectable">
		<colgroup>
			<col class="col-select">
			<col class="col-name">
			<col class="col-type">
			<col class="col-date">
			<col class="col-status">
		</colgroup>
		<thead>
			<tr>
				<th scope="col" class="col-select"></th>
				<th scope="col" class="col-name">Name</th>
				<th scope="col" class="col-type">Type</th>
				<th scope="col" class="col-date">Modified</th>
				<th scope="col" class="col-status">Status</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($entites as $entity) { ?>
				<tr class="selectable">
					<td class="col-select">
						<?= $this->render('/content/checkbox', [
							'entity'	=> $index,
							'value'		=> $entity,
						]) ?>
					</td>
					<th class="col-name" scope="row">
						<?= $entity->name ?>
					</th>
					<td class="col-type">
						<?= \Ayre::type($entity)->singular ?>
					</td>
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
<form>