<?php
$close		= isset($close) ? $close : false;
$thumbs		= isset($thumbs) ? $thumbs : false;
$selectOnly	= isset($selectOnly) ? $selectOnly : false;

$contents = $this->em($entityType->class)
	->fetchAll($index->toArray());
?>
<form action="<?= $this->url->route() ?>" class="fill">
	<div class="flex uploadable">
		<ul class="toolbar">
			<li>
				<span class="btn btn-focus uploadable-button">
					<i class="fa fa-upload"></i> Upload <?= $entityType->singular ?>
				</span>
			</li>
		</ul>
		<h1>Browse <?= $entityType->plural ?></h1>
		<?= $this->render('filters', ['filter' => $index]) ?>
		<? if ($thumbs) { ?>
			<ul class="thumbs uploadable-list multiselectable">
				<? if (count($contents)) { ?>
					<? foreach ($contents as $content) { ?>
						<li><?= $this->render('thumb', [
							'content'		=> $content,
							'selectOnly'	=> $selectOnly
						]) ?></li>
					<? } ?>
				<? } else { ?>
					<li><?= $this->render('thumb', [
						'template'		=> true,
						'selectOnly'	=> $selectOnly
					]) ?></li>
				<? } ?>		
			</ul>
		<? } else { ?>
			<table class="multiselectable">
				<colgroup>
					<col class="col-select">
					<col class="col-name">
					<col class="col-type">
					<col class="col-date">
					<col class="col-status">
				</colgroup>
				<thead>
					<tr>
						<th scope="col" class="col-select">
							<input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
						</th>
						<th scope="col" class="col-name">Content</th>
						<th scope="col" class="col-name">Type</th>
						<th scope="col" class="col-date">Modified</th>
						<th scope="col" class="col-status">Status</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($contents as $content) { ?>
						<?= $this->render('row', [
							'content'		=> $content,
							'selectOnly'	=> $selectOnly
						]) ?>
					<? } ?>
				</tbody>
			</table>
		<? } ?>
		<input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
		<script type="x-tmpl-mustache" class="uploadable-template">
			<?= $this->render('/content/thumb', ['template' => true]) ?>
		</script>
	</div>
	<div class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-focus" formmethod="post">
					<i class="fa fa-check"></i> Select <?= $entityType->singular ?>
				</button>
			</li>
		</ul>
		<? if ($close) { ?>
			<ul class="toolbar">
				<li><span class="btn modal-close">
					<i class="fa fa-times"></i>
					Close
				</span></li>
			</ul>
		<? } ?>
	</div>
<form>