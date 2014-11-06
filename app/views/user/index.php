<?php $this->layout('/layout/page_settings') ?>
<?php $this->block('main') ?>
<?php
$filter = $this->em->wrap(new \Chalk\Core\Model\Index())
	->graphFromArray($req->queryParams());
$users = $this->em($info)
	->fetchAll($filter->toArray());
?>

<ul class="toolbar">
	<li>
		<a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="btn btn-focus icon-add">
			Add <?= $info->singular ?>
		</a>
	</li>
</ul>
<h1><?= $info->plural ?></h1>
<form action="<?= $this->url->route() ?>" class="submitable">
	<ul class="filters">
		<li>
			<?= $this->render('/element/form-input', array(
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
		<?php foreach ($users as $user) { ?>
			<tr class="clickable">
				<td class="col-status">
					<span class="badge badge-status badge-<?= (int) $user->isEnabled ?>">
						<i class="icon-<?= $user->isEnabled ? 'check' : 'times' ?>"></i>ICON
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
					<?= ucfirst($user->isRoot()
						? \Chalk\Core\User::ROLE_ADMINISTRATOR
						: $user->role) ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>