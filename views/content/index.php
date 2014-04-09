<? $this->layout('/layouts/page', [
	'class' => 'upload',
]) ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<li>
		<a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="button">
			<i class="fa fa-plus"></i> Add <?= $req->type->info->singular ?>
		</a>
	</li>
</ul>
<h1><?= $req->type->info->plural ?></h1>
<?php
$contents = array_reverse($this->entity($req->type->class)->findAll());
?>
<ul class="filters">
	<li>
		<input type="search" placeholder="Search">
	</li>
	<li>
		<div><i class="fa fa-file-o"></i>Type</div>
	</li>
	<li>
		<div><i class="fa fa-calendar"></i> Date Added</div>
	</li>
	<li>
		<div><i class="fa fa-user"></i> Added By</div>
	</li>
	<li>
		<div><i class="fa fa-check-circle"></i>Draft, Pending, Published</div>
	</li>
</ul>
<table>
	<colgroup>
		<col class="col-name">
		<col class="col-create">
		<col class="col-status">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-name">Name</th>
			<th scope="col" class="col-create">Added</th>
			<th scope="col" class="col-status">Status</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($contents as $content) { ?>
			<?= $this->render('row', ['content' => $content]) ?>
		<? } ?>
	</tbody>
</table>