<ul class="meta meta-right">
	<?php if (!$content->isNewMaster()) { ?>
		<?php
		$url = $this->frontend->url($content->getObject());
		?>
		<?php if ($url) { ?>
			<li>
				<a href="<?= $url ?>" target="_blank" class="icon-view">
					View <?= $content->subtype == 'mailto'
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
		<span class="badge badge-upper badge-<?= $this->app->statusClass($content->status) ?>">
			<?= $content->status ?>
		</span>
	</li>
	<li class="icon-<?= $info->icon ?>">
		<?= $info->singular ?>
	</li>
	<!-- <li>
		Version <strong><?= $content->version ?></strong>
	</li> -->
	<?php if (!$content->isNew()) { ?>
		<li class="icon-updated">
			Updated <strong><?= $content->modifyDate->diffForHumans() ?></strong>
			by <strong><?= $content->modifyUserName ?></strong>
		</li>
	<?php } ?>
	<?= $this->partial('meta-bottom') ?>
</ul>