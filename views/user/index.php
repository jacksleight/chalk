<? $this->layout('/layouts/page_settings') ?>
<? $this->block('main') ?>
<?php
$filter = $this->em->wrap(new \Ayre\Index())
	->graphFromArray($req->queryParams());
$users = $this->em($entityType->class)
	->fetchAll($filter->toArray());
?>

<ul class="toolbar">
	<li>
		<a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="btn btn-focus">
			<i class="fa fa-plus"></i> Add <?= $entityType->singular ?>
		</a>
	</li>
</ul>
<h1><?= $entityType->plural ?></h1>
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
		<? foreach ($users as $user) { ?>
			<tr class="clickable">
				<td class="col-status">
					<span class="badge badge-status badge-<?= (int) $user->isEnabled ?>">
						<i class="fa fa-<?= $user->isEnabled ? 'check' : 'times' ?>"></i>
					</span>					
				</td>
				<th class="col-name" scope="row">
					<a href="<?= $this->url([
						'action'	=> 'edit',
						'id'		=> $user->id,
					]) ?>">
						<?= $user->name ?>
					</a>
				</th>
				<td class="col-emailAddress">
					<a href="mailto:<?= $user->emailAddress ?>">
						<?= $user->emailAddress ?>
					</a>
				</td>
				<td class="col-role">
					<?= ucfirst($user->role) ?>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>