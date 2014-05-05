<ul class="toolbar">
	<? if (!$entity->isNewMaster()) { ?>
		<li><a href="#" class="btn">
			<i class="fa fa-clock-o"></i>
			View <?= $entityType->singular ?> History
		</a></li>
	<? } ?>
</ul>