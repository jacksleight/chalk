<ul class="meta">
	<?= $this->partial('meta-top') ?>
	<li>
		<span class="badge badge-upper badge-<?= $this->app->statusClass($content->status) ?>">
			<?= $content->status ?>
		</span>
	</li>
	<!-- <li>
		Version <em><?= $content->version ?></em>
	</li> -->
	<?php if (!$content->isNew()) { ?>
		<li class="icon-updated">
			Updated <?= $content->modifyDate->diffForHumans() ?>
			by <?= $content->modifyUserName ?>
		</li>
	<?php } ?>
	<?= $this->partial('meta-bottom') ?>
</ul>