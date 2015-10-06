<?php $this->outer('/layout/page_settings') ?>
<?php $this->block('main') ?>
<?php
$index = $this->em->wrap(new \Chalk\Core\Model\Index())
	->graphFromArray($req->queryParams());
$tags = $this->em($info)
	->paged($index->toArray());
?>

<div class="flex-col">
	<div class="header">
		<ul class="toolbar toolbar-right">
			<li>
				<a href="<?= $this->url([
					'action' => 'edit',
				]) ?>" class="btn btn-focus icon-add">
					Add <?= $info->singular ?>
				</a>
			</li>
			<li>
				<a href="<?= $this->url([
					'action' => 'merge',
				]) ?>" class="btn btn-focus icon-shrink2">
					Merge <?= $info->plural ?>
				</a>
			</li>
		</ul>
		<h1><?= $info->plural ?></h1>
	</div>
	<div class="flex body">
		<div class="hanging">
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
		</div>
		<table>
			<colgroup>
				<col class="">
			</colgroup>
			<thead>
				<tr>
					<th scope="col" class="">Name</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($tags as $tag) { ?>
					<tr class="clickable">
						<th class="" scope="row">
							<a href="<?= $this->url([
								'action'	=> 'edit',
								'id'		=> $tag->id,
							]) ?>">
								<?= $tag->name ?>
							</a>
						</th>
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
		    'count'     => $tags->count(),
		]) ?>		
	</div>
</div>