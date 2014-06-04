<? if (isset($node) && !$node->isRoot()) { ?>
	<li class="space"><a href="<?= $this->url([
		'action' => 'delete'
	]) ?>" class="btn btn-negative btn-quiet confirmable">
		<i class="fa fa-times"></i>
		Remove <?= $entityType->singular ?>
	</a>
	<small>&nbsp;from <?= $structure->label ?></small>
	</li>
<? } ?>