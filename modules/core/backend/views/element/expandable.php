<?php
if (!strlen(trim($content))) {
    return;
}
?>
<div class="expandable">
	<div class="expandable-toggle icon-play3">
		<?= isset($buttonLabel) ? $buttonLabel : 'Expand' ?>
	</div>
    <div class="expandable-body">
        <?= $content ?>
    </div>
</div>