<?php
$filter = $this->em->wrap(new \Ayre\Index())
	->graphFromArray($req->queryParams());
$contents = $this->em($entityType->class)
	->fetchAll($filter->toArray());
?>

<ul class="toolbar">
	<li><a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="btn btn-focus">
			<i class="fa fa-plus"></i> New <?= $entityType->singular ?>
	</a></li>
</ul>
<h1><?= $entityType->plural ?></h1>
<?= $this->render('/content/filters', ['filter' => $filter]) ?>
<table>
	<colgroup>
		<col class="col-name">
		<col class="col-url">
		<col class="col-date">
		<col class="col-status">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-name">Name</th>
			<th scope="col" class="col-url">URL</th>
			<th scope="col" class="col-date">Updated</th>
			<th scope="col" class="col-status">Status</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($contents as $content) { ?>
			<tr class="clickable">
				<th class="col-name" scope="row">
					<a href="<?= $this->url([
						'action'	=> 'edit',
						'id'		=> $content->id,
					]) ?>"><?= $content->name ?></a>
				</th>
				<td class="col-url">
					<?= $content->url ?>
				</td>	
				<td class="col-date">
					<?= $content->modifyDate->diffForHumans() ?>
				</td>	
				<td class="col-status">
					<span class="label label-status-<?= $content->status ?>"><?= $content->status ?></span>
				</td>	
			</tr>
		<? } ?>
	</tbody>
</table>