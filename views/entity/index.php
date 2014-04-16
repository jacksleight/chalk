<? $this->layout('/layouts/page_settings') ?>
<? $this->block('main') ?>
<?php
$entites = $this->entity($entityType->class)
	->fetchAll();
?>

<ul class="toolbar">
	<li>
		<a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="btn">
			<i class="fa fa-plus"></i> Add <?= $entityType->singular ?>
		</a>
	</li>
</ul>
<h1><?= $entityType->plural ?></h1>
<table>
	<colgroup>
		<col class="col-name">
		<col class="col-create">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-name">Name</th>
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
				<td class="col-create">
					<?= getRelativeDate($entity->createDate) ?> <small>by</small>
					<?= isset($entity->createUser) ? $entity->createUser->name : 'System' ?>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>