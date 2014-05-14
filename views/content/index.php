<? $this->layout('/layouts/page_content') ?>
<? $this->block('main') ?>

<? if ($entityType->name != 'core_content') { ?>
	<?= $this->render("/{$entityType->entity->path}/index") ?>
	<?php return; ?>
<? } ?>

<?php
$contents = $this->em($entityType->class)
	->fetchAll($index->toArray());
?>
<form action="<?= $this->url->route() ?>">
	<h1>Content</h1>
	<?= $this->render('filters', ['filter' => $index]) ?>
	<table class="multiselectable">
		<colgroup>
			<col class="col-select">
			<col class="col-type">
			<col class="col-name">
			<col class="col-date">
			<col class="col-status">
		</colgroup>
		<thead>
			<tr>
				<th scope="col" class="col-select">
					<input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
				</th>
				<th scope="col" class="col-name">Type</th>
				<th scope="col" class="col-name">Content</th>
				<th scope="col" class="col-date">Modified</th>
				<th scope="col" class="col-status">Status</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($contents as $content) { ?>
				<tr class="selectable clickable">
					<td class="col-select">
						<?= $this->render('/content/checkbox', [
							'entity'	=> $index,
							'value'		=> $content,
						]) ?>
					</td>
					<td>
						<a href="<?= $this->url([
							'action'	=> 'index',
							'entityType'=> \Ayre::type($content)->slug,
						]) ?>"><?= $content->typeLabel ?></a>
					</td>
					<th class="col-name" scope="row">
						<? if ($content instanceof \Ayre\Entity\File && $content->file->exists() && $content->isGdCompatible()) { ?>
							<img src="<?= $this->image(
								$content->file,
								'resize',
								['size' => '46', 'crop' => true]
							) ?>">
						<? } ?>
						<a href="<?= $this->url([
							'action'	=> 'edit',
							'entityType'=> \Ayre::type($content)->slug,
							'id'		=> $content->id,
						]) ?>"><?= $content->name ?></a><br>
						<small><?= $content->subname ?></small>
					</th>
					<td class="col-date">
						<?= $content->modifyDate->diffForHumans() ?><br>
						<small>by <?= $content->modifyUserName ?></small>
					</td>
					<td class="col-status">
						<span class="badge badge-status badge-<?= $content->status ?>"><?= $content->status ?></span>
					</td>	
				</tr>
			<? } ?>
		</tbody>
	</table>
<form>