<?php
$structure	= $this->em('Chalk\Core\Structure')->id($req->structure);
$content	= $node->content;
$entity	= \Chalk\Chalk::entity($content->getObject());
?>

<?php $this->layout('/layouts/page_structure') ?>
<?php $this->block('main') ?>

<?php if ($entity->class != 'Chalk\Core\Content') { ?>
	<?= $this->render("/{$entity->local->path}/form", [
		'structure'	=> $structure,
		'content'	=> $content,
		'entity'	=> $entity,
	], $entity->module->class) ?>
<?php } ?>