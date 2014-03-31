<?php
$id = $req->getParam('id');
$wrap = $this->entity->wrap($user = isset($id)
	? $this->em->getRepository('\App\User')->find($id)
	: new \App\User());

$req->addParams(array(
	'title' => $user->isPersisted()
		? "Edit User – {$user->name}"
		: "Add User",
), 'meta');
?>

<? if ($user->isPersisted()) { ?>
	<div class="btn-group pull-right">
		<a href="<?= $this->url(array(
			'action' => 'delete',
		)) ?>" class="btn btn-danger">Delete User</button>
	</div>
<? } ?>
<h1 class="page-header">
	<? if ($user->isPersisted()) { ?>
		Edit User
		<small><?= $user->name ?></small>
	<? } else { ?>
		Add User
	<? } ?>
</h1>
<form action="<?= $this->url() ?>" class="form-horizontal" method="post" novalidate>
	<fieldset>
		<?= $this->render('/_form-item', array(
			'entity'	=> $wrap,
			'name'		=> 'name',
		)) ?>
		<?= $this->render('/_form-item', array(
			'entity'	=> $wrap,
			'name'		=> 'emailAddress',
		)) ?>
		<div class="form-group">
			<div class="col-lg-2"></div>
			<div class="col-lg-10">
				<? if ($user->isPersisted()) { ?>
					<div class="btn-group pull-right">
						<a href="<?= $this->url(array(
							'action' => 'delete',
						)) ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete User</button>
					</div>
				<? } ?>
				<button class="btn btn-success" type="submit">Save Changes</button>
			</div>
		</div>
	</fieldset>
</form>