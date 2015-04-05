<?php $this->parent('/layout/page_settings') ?>
<?php $this->block('main') ?>
<?php
$index = $this->em->wrap(new \Chalk\Core\Model\Index())
	->graphFromArray($req->queryParams());
$users = $this->em($info)
	->paged($index->toArray(), null, $index->limit, $index->page);
?>

<div class="flex-col">
	<div class="flex body">
		<ul class="toolbar toolbar-right">
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
			<ul class="toolbar">
				<li class="flex">
					<?= $this->render('/element/form-input', array(
						'type'			=> 'input_search',
						'entity'		=> $index,
						'name'			=> 'search',
						'placeholder'	=> 'Search…',
					)) ?>
				</li>
			</ul>
		</form>
		<table>
			<colgroup>
				<col class="col-badge">
				<col class="">
				<col class="col-right">
			</colgroup>
			<thead>
				<tr>
					<th scope="col" class="">Enabled</th>
					<th scope="col" class="">Name</th>
					<th scope="col" class="col-right">Role</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user) { ?>
					<tr class="clickable">
						<td class="col-badge">
							<span class="badge badge-center badge-<?= $user->isEnabled ? 'positive' : 'negative' ?> icon-<?= $user->isEnabled ? 'true' : 'false' ?>"></span>	
						</td>
						<th class="" scope="row">
							<a href="<?= $this->url([
								'action'	=> 'edit',
								'id'		=> $user->id,
							]) ?>">
								<?= $user->name ?>
							</a>
						</th>
						<td class="col-right">
							<?= ucfirst($user->role) ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="footer">
		<?= $this->render('/element/form-input', [
		    'type'      => 'paginator',
		    'entity'    => $index,
		    'name'      => 'page',
		    'limit'     => $index->limit,
		    'count'     => $users->count(),
		]) ?>		
	</div>
</div>