<?php
$filter = $this->em->wrap(new \Ayre\Index())
	->graphFromArray($req->queryParams());
$entites = $this->em($entityType->class)
	->fetchAll($filter->toArray());
?>

<form action="<?= $this->url->route() ?>" class="uploadable">
	<ul class="toolbar">
		<li>
			<span class="btn btn-focus uploadable-button">
				<i class="fa fa-upload"></i> Upload <?= $entityType->singular ?>
			</span>
		</li>
	</ul>
	<h1><?= $entityType->plural ?></h1>
	<?= $this->render('/content/filters', ['filter' => $filter]) ?>
	<ul class="thumbs uploadable-list multiselectable">
		<? if (count($entites)) { ?>
			<? foreach ($entites as $entity) { ?>
				<?= $this->render('thumb', ['entity' => $entity]) ?>
			<? } ?>
		<? } else { ?>
			<?= $this->render('thumb', ['template' => true]) ?>
		<? } ?>		
	</ul>
	<input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
	<script type="x-tmpl-mustache" class="uploadable-template">
		<?= $this->render('thumb', ['template' => true]) ?>
	</script>
</form>