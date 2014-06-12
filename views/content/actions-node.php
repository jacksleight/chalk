<? if (isset($node) && !$node->isRoot()) { ?>
	<li class="space"><a href="<?= $this->url([
		'action' => 'delete'
	]) ?>" class="btn btn-negative btn-quiet confirmable">
		<i class="fa fa-times"></i>
		Remove <?= $entity->singular ?>
	</a>
	<small>&nbsp;from <?= $structure->label ?></small>
	</li>
<? } ?>