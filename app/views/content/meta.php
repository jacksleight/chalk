<ul class="meta">
	<li>
		<span class="badge badge-status badge-<?= $content->status ?>">
			<?= $content->status ?>
		</span>
	</li>
	<!-- <li>
		Version <em><?= $content->version ?></em>
	</li> -->
	<?php if (!$content->isNew()) { ?>
		<li class="icon icon-updated-dark">
			Updated <em><?= $content->modifyDate->diffForHumans() ?></em>
			by <em><?= $content->modifyUserName ?></em>
		</li>
	<?php } ?>
</ul>