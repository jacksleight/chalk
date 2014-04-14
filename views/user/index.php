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
		]) ?>" class="button">
			<i class="fa fa-plus"></i> Add <?= $entityType->info->singular ?>
		</a>
	</li>
</ul>
<h1><?= $entityType->info->plural ?></h1>
<table>
	<colgroup>
		<col class="col-status">
		<col class="col-name">
		<col class="col-emailAddress">
		<col class="col-role">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-status">Enabled</th>
			<th scope="col" class="col-name">Name</th>
			<th scope="col" class="col-emailAddress">Email Address</th>
			<th scope="col" class="col-role">Role</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($entites as $entity) { ?>
			<tr class="linkable">
				<td class="col-status">
					<span class="label status status-<?= (int) $entity->isEnabled ?>">
						<i class="fa fa-<?= $entity->isEnabled ? 'check' : 'times' ?>"></i>
					</span>					
				</td>
				<th class="col-name" scope="row">
					<a href="<?= $this->url([
						'action'	=> 'edit',
						'id'		=> $entity->id,
					]) ?>">
						<?= $entity->name ?>
					</a>
				</th>
				<td class="col-emailAddress">
					<a href="mailto:<?= $entity->emailAddress ?>">
						<?= $entity->emailAddress ?>
					</a>
				</td>
				<td class="col-role">
					<?= ucfirst($entity->role) ?>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>