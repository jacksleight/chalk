<?php
$users = $this->em->getRepository('\App\User')->findAll();

$req->addParams(array(
	'title' => 'Users',
), 'meta');
?>

<div class="btn-group pull-right">
	<a href="<?= $this->url(array(
		'action' => 'add',
	)) ?>" class="btn btn-primary">Add User</button>
</div>
<h1 class="page-header">Users</h1>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($users as $user) { ?>
			<tr>
				<th>
					<a href="<?= $this->url(array(
						'action'	=> 'edit',
						'id'		=> $user->id,
					)) ?>"><?= $user->name ?></a>
				</th>
			</tr>
		<? } ?>
	</tbody>
</table>