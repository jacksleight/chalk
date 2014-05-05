<?php
$entites = $this->em($entityType->class)
	->fetchAll($index->toArray());
?>
<form action="<?= $this->url->route() ?>">
	<ul class="toolbar">
		<li>
			<button class="btn btn-focus" formmethod="post">
				<i class="fa fa-plus"></i> Add to <?= $struct->label ?>	
			</button>
		</li>
	</ul>
	<h1>Select Content</h1>
	<?= $this->render('filters', ['filter' => $index]) ?>
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
				<th scope="col" class="col-name">Content</th>
				<th scope="col" class="col-date">Modified</th>
				<th scope="col" class="col-status">Status</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($entites as $entity) { ?>
				<tr class="selectable">
					<td class="col-select">
						<?= $this->render('/content/checkbox', [
							'entity'	=> $index,
							'value'		=> $entity,
						]) ?>
					</td>
					<th class="col-name" scope="row">
						<? if ($entity instanceof \Ayre\Entity\File && $entity->file->exists() && $entity->isGdCompatible()) { ?>
							<img src="<?= $this->image(
								$entity->file,
								'resize',
								['size' => '46', 'crop' => true]
							) ?>">
						<? } ?>
						<?= $entity->name ?>
						<small>
							<?= $entity->typeLabel ?>
							<? if (isset($entity->subtype)) { ?>
								– <?= $entity->subtypeLabel ?>
							<? } ?>
						</small>
					</th>
					<td class="col-date">
						<?= $entity->modifyDate->diffForHumans() ?>
						<small>by <?= $entity->modifyUserName ?></small>
					</td>
					<td class="col-status">
						<span class="label label-status-<?= $entity->status ?>"><?= $entity->status ?></span>
					</td>	
				</tr>
			<? } ?>
		</tbody>
	</table>
<form>