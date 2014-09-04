<ul class="meta">
	<li>
		<span class="badge badge-status badge-<?= $content->status ?>">
			<?= $content->status ?>
		</span>
	</li>
	<!-- <li>
		<i class="fa fa-asterisk"></i>
		Version <em><?= $content->version ?></em>
	</li> -->
	<? if (!$content->isNew()) { ?>
		<li>
			<i class="fa fa-calendar"></i>
			Updated <em><?= $content->modifyDate->diffForHumans() ?></em>
			by <em><?= $content->modifyUserName ?></em>
		</li>
	<? } ?>
</ul>