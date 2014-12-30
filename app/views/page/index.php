<?php
$filter = $this->em->wrap(new \Chalk\Core\Model\Index())
	->graphFromArray($req->queryParams());
$contents = $this->em($info)
	->paged($filter->toArray(), null, $filter->limit, $filter->page);
?>

<form action="<?= $this->url->route() ?>">
	<ul class="toolbar">
		<li><a href="<?= $this->url([
				'action' => 'edit',
			]) ?>" class="btn btn-focus icon-add">
				New <?= $info->singular ?>
		</a></li>
	</ul>
	<h1><?= $info->plural ?></h1>
	<?= $this->render('/content/filters', ['filter' => $filter]) ?>
	<table class="multiselectable">
		<colgroup>
			<col class="col-select">
			<col class="col-name">
			<col class="col-date">
			<col class="col-badge">
		</colgroup>
		<thead>
			<tr>
				<th scope="col" class="col-select">
					<input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
				</th>
				<th scope="col" class="col-name">Page</th>
				<th scope="col" class="col-date">Updated</th>
				<th scope="col" class="col-badge">Status</th>
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
					<td class="col-badge">
						<span class="badge badge-status badge-<?= $content->status ?>"><?= $content->status ?></span>
					</td>	
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<ul class="toolbar right autosubmitable">
		<li>
			Show 
			<?= $this->render('/element/form-input', array(
				'type'			=> 'select',
				'entity'		=> $filter,
				'name'			=> 'limit',
				'null'			=> 'All',
			)) ?>
		</li>
	</ul>
	<?= $this->render('/element/paginator', [
		'limit'	=> $filter->limit,
		'page'	=> $filter->page,
		'count'	=> $contents->count(),
	]) ?>
</form>