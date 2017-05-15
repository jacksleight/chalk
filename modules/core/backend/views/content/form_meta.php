<?php
use Chalk\Chalk;
?>

<ul class="meta meta-right">
	<?php if (!$entity->isNew()) { ?>
		<?php
		$url = $this->frontend->url($entity->getObject());
		?>
		<?php if ($url) { ?>
			<li>
				<a href="<?= $url ?>" target="_blank" class="icon-view">
					<?= $entity->status != Chalk::STATUS_PUBLISHED ? 'Preview' : 'View' ?> <?= $entity->subtype == 'mailto'
			            ? str_replace('Link', 'strongail Link', $info->singular)
			            : $info->singular ?>
				</a>
			</li>
		<?php } ?>
	<?php } ?>
</ul>
<ul class="meta">
	<?= $this->partial('meta-top') ?>
	<li>
		<span class="badge badge-upper badge-<?= $this->app->statusClass($entity->status) ?>">
			<?= $entity->status ?>
		</span>
	</li>
	<li class="icon-<?= $info->icon ?>">
		<?= $info->singular ?>
	</li>
	<!-- <li>
		Version <strong><?= $entity->version ?></strong>
	</li> -->
	<?php if (!$entity->isNew()) { ?>
		<li class="icon-updated">
			Updated <strong><?= $entity->modifyDate->diffForHumans() ?></strong>
			by <strong><?= $entity->modifyUserName ?></strong>
		</li>
	<?php } ?>
	<?= $this->partial('meta-bottom') ?>
</ul>