<?php
$contents = $this->em($entityType->class)
	->fetchAll($index->toArray());
?>
<form action="<?= $this->url->route() ?>" class="fill">
	<div class="flex">
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
				<? foreach ($contents as $content) { ?>
					<tr class="selectable">
						<td class="col-select">
							<?= $this->render('/content/checkbox', [
								'entity'	=> $index,
								'value'		=> $content,
							]) ?>
						</td>
						<th class="col-name" scope="row">
							<? if ($content instanceof \Ayre\Core\File && $content->file->exists() && $content->isGdCompatible()) { ?>
								<img src="<?= $this->image(
									$content->file,
									'resize',
									['size' => '46', 'crop' => true]
								) ?>">
							<? } ?>
							<?= $content->name ?><br>
							<small>
								<?= $content->typeLabel ?>
								<? if (isset($content->subtype)) { ?>
									– <?= $content->subtypeLabel ?>
								<? } ?>
							</small>
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
	</div>
	<div class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-focus" formmethod="post">
					<i class="fa fa-plus"></i> Add to <?= $struct->label ?>	
				</button>
			</li>
		</ul>
	</div>
<form>