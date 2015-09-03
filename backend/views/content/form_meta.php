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
			Updated <em><?= $content->modifyDate->diffForHumans() ?></em>
			by <em><?= $content->modifyUserName ?></em>
		</li>
		<?php
		$url = $this->frontend->url($content->getObject());
		?>
		<?php if ($url) { ?>
			<li class="icon-link">
				<a href="<?= $url ?>" target="_blank"><?= $url ?></a>
			</li>
		<?php } ?>
	<?php } ?>
	<?= $this->partial('meta-bottom') ?>
</ul>