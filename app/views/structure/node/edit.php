<?php
$structure	= $this->em('Chalk\Core\Structure')->id($req->structure);
$content	= $node->content;
$info	    = \Chalk\Chalk::info($content->getObject());
?>

<?php $this->parent('/layout/page_structure') ?>
<?php $this->block('main') ?>

<?php if ($info->class != 'Chalk\Core\Content') { ?>
	<?= $this->child("/{$info->local->path}/form", [
		'structure'	=> $structure,
		'content'	=> $content,
		'info'	    => $info,
	], $info->module->class) ?>
<?php } ?>