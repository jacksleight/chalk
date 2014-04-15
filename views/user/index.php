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
		]) ?>" class="btn">
			<i class="fa fa-plus"></i> Add <?= $entityType->info->singular ?>
		</a>
	</li>
</ul>
<h1><?= $entityType->info->plural ?></h1>
<form action="<?= $this->url->route() ?>" class="submitable">
	<ul class="filters">
		<li>
			<?= $this->render('/elements/form-input', array(
				'type'			=> 'input_search',
				'entity'		=> $filter,
				'name'			=> 'search',
				'placeholder'	=> 'Search…',
			)) ?>
		</li>
		<li>
			<?= $this->render('/elements/form-input', array(
				'type'			=> 'dropdown_single',
				'entity'		=> $filter,
				'null'			=> 'Any',
				'name'			=> 'createDateMin',
				'icon'			=> 'calendar',
				'placeholder'	=> 'Date Added',
			)) ?>
		</li>
		<li>
			<?= $this->render('/elements/form-input', array(
				'type'			=> 'dropdown_multiple',
				'entity'		=> $filter,
				'name'			=> 'createUsers',
				'icon'			=> 'user',
				'placeholder'	=> 'Added By',
				'values'		=> []
			)) ?>
		</li>
	</ul>
</form>
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