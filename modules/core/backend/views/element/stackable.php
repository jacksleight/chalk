<?php
$sortable = isset($sortable) ? $sortable : true;
?>
<div class="stackable" data-sortable="<?= $sortable ?>">
	<ul class="stackable-items">
		<?php foreach ($items as $i => $item) { ?>
			<li class="stackable-item">
				<div class="stackable-controls">
					<? if ($sortable) { ?>
						<span class="btn btn-icon stackable-move icon-move"><span>Move</span></span>
					<? } ?>
					<span class="btn btn-icon stackable-delete icon-delete"><span>Delete</span></span>
				</div>
				<div class="stackable-body">
					<?= $item ?>
				</div>
			</li>
		<?php } ?>
    </ul>
    <span class="btn btn-lighter btn-out stackable-add icon-add">
    	Add
    </span>
    <span class="btn btn-lighter btn-out stackable-add-multiple icon-add">
    	Add Multiple
    </span>
    <script type="x-tmpl-mustache" class="stackable-template">
		<li class="stackable-item">
			<div class="stackable-controls">
				<? if ($sortable) { ?>
					<span class="btn btn-icon btn-light stackable-move icon-move"><span>Move</span></span>
				<? } ?>
				<span class="btn btn-icon btn-light stackable-delete icon-delete"><span>Delete</span></span>
			</div>
			<div class="stackable-body">
				<?= $template ?>
			</div>
		</li>
    </script>
</div>