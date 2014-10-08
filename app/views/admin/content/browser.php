<?php
$method		= isset($method) ? $method : 'get';
$close		= isset($close) ? $close : false;
$thumbs		= isset($thumbs) ? $thumbs : false;

$contents = $this->em($entity)
	->all($index->toArray());
?>
<form action="<?= $this->url->route() ?>" class="fill" data-modal-size="fullscreen" method="<?= $method ?>">
	<?= $this->render('/elements/form-input', array(
		'type'			=> 'input_hidden',
		'entity'		=> $index,
		'name'			=> 'entity',
	)) ?>
	<div class="flex <?= $entity->class == 'Chalk\Core\File' ? 'uploadable' : null ?>">
		<ul class="toolbar">
			<?php if ($entity->class == 'Chalk\Core\File') { ?>
				<li>
					<span class="btn btn-focus uploadable-button">
						<i class="fa fa-upload"></i> Upload <?= $entity->singular ?>
					</span>
				</li>
			<?php } ?>
		</ul>
		<h1><?= $entity->plural ?></h1>
		<?= $this->render('filters', ['filter' => $index]) ?>
		<?php if ($thumbs) { ?>
			<ul class="thumbs multiselectable <?= $entity->class == 'Chalk\Core\File' ? 'uploadable-list' : null ?>">
				<?php if (count($contents)) { ?>
					<?php foreach ($contents as $content) { ?>
						<li><?= $this->render('thumb', [
							'content'	=> $content,
							'link'		=> false
						]) ?></li>
					<?php } ?>
				<?php } else { ?>
					<li><?= $this->render('thumb', [
						'template'	=> true,
						'link'		=> false
					]) ?></li>
				<?php } ?>		
			</ul>
		<?php } else { ?>
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
					<?php foreach ($contents as $content) { ?>
						<?= $this->render('row', [
							'content'	=> $content,
							'link'		=> false
						]) ?>
					<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		<?php if ($entity->class == 'Chalk\Core\File') { ?>
			<input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
			<script type="x-tmpl-mustache" class="uploadable-template">
				<?= $this->render('/content/thumb', ['template' => true]) ?>
			</script>
		<?php } ?>
	</div>
	<div class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-focus" formmethod="post">
					<i class="fa fa-check"></i> Select <?= $entity->singular ?>
				</button>
			</li>
		</ul>
		<?php if ($close) { ?>
			<ul class="toolbar">
				<li><span class="btn modal-close">
					<i class="fa fa-times"></i>
					Close
				</span></li>
			</ul>
		<?php } ?>
	</div>
<form>