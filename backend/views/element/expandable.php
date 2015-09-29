<?php
echo $content;
return;
?>
<div class="expandable">
	<div class="expandable-body">
		<?= $content ?>
	</div>
	<div class="expandable-toggle btn btn-lightest btn-block btn-out btn icon-plus">
		<?= isset($buttonLabel) ? $buttonLabel : 'Expand' ?>
	</div>
</div>