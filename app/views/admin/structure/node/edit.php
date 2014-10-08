<?php
$structure	= $this->em('core_structure')->id($req->structure);
$content	= $node->content;
$entity	= \Chalk::entity($content->getObject());
?>

<?php $this->layout('/layouts/page_structure') ?>
<?php $this->block('main') ?>

<?php if ($entity->name != 'core_content') { ?>
	<?= $this->render("/{$entity->local->path}/form", [
		'structure'	=> $structure,
		'content'	=> $content,
		'entity'	=> $entity,
	], $entity->module->name) ?>
<?php } ?>