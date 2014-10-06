<?php
$structure	= $this->em('core_structure')->id($req->structure);
$content	= $node->content;
$entity	= \Chalk::entity($content->getObject());
?>

<? $this->layout('/layouts/page_structure') ?>
<? $this->block('main') ?>

<? if ($entity->name != 'core_content') { ?>
	<?= $this->render("/{$entity->local->path}/form", [
		'structure'	=> $structure,
		'content'	=> $content,
		'entity'	=> $entity,
	], $entity->module->name) ?>
<? } ?>