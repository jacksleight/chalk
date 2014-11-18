<?php if (isset($node) && !$node->isRoot()) { ?>
	<li><a href="<?= $this->url([
		'action' => 'delete'
	]) ?>" class="btn btn-negative btn-quiet confirmable icon-remove">
		Remove <?= $info->singular ?> <small>from <strong><?= $structure->name ?></strong></small>
	</a>
	</li>
<?php } ?>