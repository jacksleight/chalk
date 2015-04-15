<?php $this->parent('/layout/page_structure', [
	'content' => $content,
]) ?>
<?php $this->block('main') ?>

<?php
if (!isset($node->content)) {
	return;
}
$structure	= $this->em('Chalk\Core\Structure')->id($req->structure);
$content	= $node->content;
$info	    = \Chalk\Chalk::info($content->getObject());
?>

<?php if ($info->class != 'Chalk\Core\Content') { ?>
	<form action="<?= $this->url->route() ?><?= $this->url->query() ?>" method="post">
		<?= $this->child("/{$info->local->path}/form", [
			'structure'	=> $structure,
			'content'	=> $content,
			'info'	    => $info,
		], $info->module->name) ?>
	</form>
<?php } ?>