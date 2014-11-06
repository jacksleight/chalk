<?php if (isset($node) && !$node->isRoot()) { ?>
	<li><a href="<?= $this->url([
		'action' => 'delete'
	]) ?>" class="btn btn-negative btn-quiet confirmable icon-remove">
		Remove <?= $info->singular ?>
	</a>
	<small>&nbsp;from <strong><?= $structure->name ?></strong> structure</small>
	</li>
<?php } ?>