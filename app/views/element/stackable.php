<div class="stackable">
	<ul class="stackable-items">
		<? foreach ($items as $i => $item) { ?>
			<li class="stackable-item">
				<div class="stackable-controls">
					<span class="btn btn-icon btn-quieter btn-active stackable-move icon-drag">Move</span>
					<span class="btn btn-icon btn-quieter btn-negative stackable-delete icon-delete">Delete</span>
				</div>
				<div class="stackable-body">
					<?= $item ?>
				</div>
			</li>
		<? } ?>
    </ul>
    <span class="btn stackable-add icon-add">
    	Add <?= isset($stackableLabel) ? $stackableLabel : 'Item' ?>
    </span>
    <script type="x-tmpl-mustache" class="stackable-template">
		<li class="stackable-item">
			<div class="stackable-controls">
				<span class="btn btn-icon btn-quieter btn-active stackable-move icon-drag">Move</span>
				<span class="btn btn-icon btn-quieter btn-negative stackable-delete icon-delete">Delete</span>
			</div>
			<div class="stackable-body">
				<?= $template ?>
			</div>
		</li>    	
    </script>
</div>