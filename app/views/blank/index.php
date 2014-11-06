<?php
$filter = $this->em->wrap(new \Chalk\Core\Model\Index())
	->graphFromArray($req->queryParams());
$contents = $this->em($info)
	->fetchAll($filter->toArray());
?>

<ul class="toolbar">
	<li><a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="btn btn-focus icon-add">
			New <?= $info->singular ?>
	</a></li>
</ul>
<h1><?= $info->plural ?></h1>
<?= $this->render('/content/filters', ['filter' => $filter]) ?>
<table>
	<colgroup>
		<col class="col-name">
		<col class="col-date">
		<col class="col-status">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-name">Route</th>
			<th scope="col" class="col-date">Updated</th>
			<th scope="col" class="col-status">Status</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($contents as $content) { ?>
			<tr class="clickable">
				<th class="col-name" scope="row">
					<a href="<?= $this->url([
						'action'	=> 'edit',
						'content'	=> $content->id,
					]) ?>"><?= $content->name ?></a><br>
				</th>
				<td class="col-date">
					<?= $content->modifyDate->diffForHumans() ?><br>
					<small>by <?= $content->modifyUserName ?></small>
				</td>	
				<td class="col-status">
					<span class="badge badge-status badge-<?= $content->status ?>"><?= $content->status ?></span>
				</td>	
			</tr>
		<?php } ?>
	</tbody>
</table>