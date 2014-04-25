<ul class="meta">
	<li>
		<span class="label label-status-<?= $entity->status ?>">
			<?= $entity->status ?>
		</span>
	</li>
	<li>
		<i class="fa fa-asterisk"></i>
		Version <em><?= $entity->version ?></em>
	</li>
	<? if (!$entity->isNew()) { ?>
		<li>
			<i class="fa fa-calendar"></i>
			Updated <em><?= $entity->modifyDate->diffForHumans() ?></em>
			by <em><?= $entity->modifyUserName ?></em>
		</li>
	<? } ?>
</ul>