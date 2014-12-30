<?php
$method		= isset($method) ? $method : 'get';
$close		= isset($close) ? $close : false;
$thumbs		= isset($thumbs) ? $thumbs : false;

$contents = $this->em($info)
	->all($index->toArray());
?>
<form action="<?= $this->url->route() ?>" class="fill" data-modal-size="fullscreen" method="<?= $method ?>">
	<?= $this->render('/element/form-input', array(
		'type'			=> 'input_hidden',
		'entity'		=> $index,
		'name'			=> 'entity',
	)) ?>
	<div class="flex <?= $info->class == 'Chalk\Core\File' ? 'uploadable' : null ?>">
		<ul class="toolbar">
			<?php if ($info->class == 'Chalk\Core\File') { ?>
				<li>
					<span class="btn btn-focus uploadable-button icon-upload">
						Upload <?= $info->singular ?>
					</span>
				</li>
			<?php } ?>
		</ul>
		<h1><?= $info->plural ?></h1>
		<?= $this->child('filters', ['filter' => $index]) ?>
		<?php if ($thumbs) { ?>
			<ul class="thumbs multiselectable <?= $info->class == 'Chalk\Core\File' ? 'uploadable-list' : null ?>">
				<?php if (count($contents)) { ?>
					<?php foreach ($contents as $content) { ?>
						<li><?= $this->child('thumb', [
							'content'	=> $content,
							'link'		=> false
						]) ?></li>
					<?php } ?>
				<?php } else { ?>
					<li><?= $this->child('thumb', [
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
					<col class="col-badge">
				</colgroup>
				<thead>
					<tr>
						<th scope="col" class="col-select">
							<input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
						</th>
						<th scope="col" class="col-name">Content</th>
						<th scope="col" class="col-date">Modified</th>
						<th scope="col" class="col-badge">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($contents as $content) { ?>
						<?= $this->child('row', [
							'content'	=> $content,
							'link'		=> false
						]) ?>
					<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		<?php if ($info->class == 'Chalk\Core\File') { ?>
			<input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
			<script type="x-tmpl-mustache" class="uploadable-template">
				<?= $this->child('/content/thumb', ['template' => true]) ?>
			</script>
		<?php } ?>
	</div>
	<div class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-positive icon-ok" formmethod="post">
					Select <?= $info->singular ?>
				</button>
			</li>
		</ul>
		<?php if ($close) { ?>
			<ul class="toolbar">
				<li><span class="btn modal-close icon-cancel">
					Close
				</span></li>
			</ul>
		<?php } ?>
	</div>
<form>