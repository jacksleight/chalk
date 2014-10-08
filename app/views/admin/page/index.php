<?php
$filter = $this->em->wrap(new \Chalk\Core\Model\Index())
	->graphFromArray($req->queryParams());
$contents = $this->em($entity)
	->all($filter->toArray());
?>

<form action="<?= $this->url->route() ?>">
	<ul class="toolbar">
		<li><a href="<?= $this->url([
				'action' => 'edit',
			]) ?>" class="btn btn-focus">
				<i class="fa fa-plus"></i> New <?= $entity->singular ?>
		</a></li>
	</ul>
	<h1><?= $entity->plural ?></h1>
	<?= $this->render('/content/filters', ['filter' => $filter]) ?>
	<table class="multiselectable">
		<colgroup>
			<col class="col-select">
			<col class="col-name">
			<col class="col-date">
			<col class="col-status">
		</colgroup>
		<thead>
			<tr>
				<th scope="col" class="col-select">
					<input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
				</th>
				<th scope="col" class="col-name">Page</th>
				<th scope="col" class="col-date">Updated</th>
				<th scope="col" class="col-status">Status</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($contents as $content) { ?>
				<tr class="clickable selectable">
					<td class="col-select">
						<?= $this->render('/content/checkbox', [
							'entity'	=> $index,
							'value'		=> $content,
						]) ?>
					</td>
					<th class="col-name" scope="row">
						<a href="<?= $this->url([
							'action'	=> 'edit',
							'content'	=> $content->id,
						]) ?>"><?= $content->name ?></a><br>
					</th>
					<td class="col-date">
						<?= $content->modifyDate->diffForHumans() ?>
						<small>by <?= $content->modifyUserName ?></small>
					</td>	
					<td class="col-status">
						<span class="badge badge-status badge-<?= $content->status ?>"><?= $content->status ?></span>
					</td>	
				</tr>
			<?php } ?>
		</tbody>
	</table>
</form>