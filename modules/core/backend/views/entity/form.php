<div class="flex-col">
	<fieldset class="header">
		<?= $this->partial('tools') ?>
		<?= $this->partial('header') ?>
		<?php 
        $meta = $this->partial('meta-secondary') . $this->partial('meta-primary');
        ?>
        <?php if (strpos($meta, '</li>') !== false) { ?>
            <div class="hanging">
                <?= $meta ?>
            </div>
        <?php } ?>
	</fieldset>
	<div class="flex body">
		<?= $this->partial('body') ?>
        <?php // $this->partial('node') ?>
        <?php if ($this->user->isDeveloper()) { ?>
            <?= $this->partial('developer') ?>
        <?php } ?>
	</div>
	<fieldset class="footer">
		<?= $this->partial('actions-primary') ?>
		<?= $this->partial('actions-secondary') ?>
	</fieldset>
</div>