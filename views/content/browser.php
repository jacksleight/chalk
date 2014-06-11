<?php
$close		= isset($close) ? $close : false;
$thumbs		= isset($thumbs) ? $thumbs : false;

$contents = $this->em($entityType->class)
	->fetchAll($index->toArray());
?>
<form action="<?= $this->url->route() ?>" class="fill" data-modal-size="fullscreen">
	<?= $this->render('/elements/form-input', array(
		'type'			=> 'input_hidden',
		'entity'		=> $index,
		'name'			=> 'entityType',
	)) ?>
	<div class="flex <?= $entityType->name == 'core_file' ? 'uploadable' : null ?>">
		<ul class="toolbar">
			<? if ($entityType->name == 'core_file') { ?>
				<li>
					<span class="btn btn-focus uploadable-button">
						<i class="fa fa-upload"></i> Upload <?= $entityType->singular ?>
					</span>
				</li>
			<? } ?>
		</ul>
		<h1><?= $entityType->plural ?></h1>
		<?= $this->render('filters', ['filter' => $index]) ?>
		<? if ($thumbs) { ?>
			<ul class="thumbs multiselectable <?= $entityType->name == 'core_file' ? 'uploadable-list' : null ?>">
				<? if (count($contents)) { ?>
					<? foreach ($contents as $content) { ?>
						<li><?= $this->render('thumb', [
							'content'	=> $content,
							'link'		=> false
						]) ?></li>
					<? } ?>
				<? } else { ?>
					<li><?= $this->render('thumb', [
						'template'	=> true,
						'link'		=> false
					]) ?></li>
				<? } ?>		
			</ul>
		<? } else { ?>
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
						<?= $this->render('row', [
							'content'	=> $content,
							'link'		=> false
						]) ?>
					<? } ?>
				</tbody>
			</table>
		<? } ?>
		<? if ($entityType->name == 'core_file') { ?>
			<input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
			<script type="x-tmpl-mustache" class="uploadable-template">
				<?= $this->render('/content/thumb', ['template' => true]) ?>
			</script>
		<? } ?>
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