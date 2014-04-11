<? $this->layout('/layouts/page_settings') ?>
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
<table>
	<colgroup>
		<col class="col-name">
		<col class="col-emailAddress">
		<col class="col-create">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-name">Name</th>
			<th scope="col" class="col-name">Email Address</th>
			<th scope="col" class="col-create">Added</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($entites as $entity) { ?>
			<tr class="linkable">
				<th class="col-name" scope="row">
					<a href="<?= $this->url([
						'action'	=> 'edit',
						'id'		=> $entity->id,
					]) ?>">
						<?= $entity->name ?>
					</a>
				</th>
				<td>
					<a href="mailto:<?= $entity->emailAddress ?>">
						<i class="fa fa-external-link"></i>
						<?= $entity->emailAddress ?>
					</a>
				</td>
				<td class="col-create">
					<?= getRelativeDate($entity->createDate) ?> <small>by</small>
					<?= isset($entity->createUser) ? $entity->createUser->name : 'System' ?>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>