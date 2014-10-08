<?php $this->layout('/layouts/page_settings') ?>
<?php $this->block('main') ?>
<?php
$structures = $this->em($entity)
	->fetchAll();
?>

<ul class="toolbar">
	<li>
		<a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="btn btn-focus">
			<i class="fa fa-plus"></i> New <?= $entity->singular ?>
		</a>
	</li>
</ul>
<h1><?= $entity->plural ?></h1>
<table>
	<colgroup>
		<col class="col-name">
		<col class="col-date">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-name">Name</th>
			<th scope="col" class="col-date">Added</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($structures as $structure) { ?>
			<tr class="linkable">
				<th class="col-name" scope="row">
					<a href="<?= $this->url([
						'action'	=> 'edit',
						'id'		=> $structure->id,
					]) ?>">
						<?= $structure->name ?>
					</a>
				</th>
				<td class="col-date">
					<?= $structure->createDate->diffForHumans() ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>