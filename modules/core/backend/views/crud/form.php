<?php
$this->params([
    'isAddAllowed'    => $isAddAllowed    = isset($isAddAllowed)    ? $isAddAllowed    : true,
    'isDeleteAllowed' => $isDeleteAllowed = isset($isDeleteAllowed) ? $isDeleteAllowed : true,
]);
?>

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
		<?= $this->partial('general') ?>
        <?php if (is_a($info->class, 'Chalk\Core\Behaviour\Publishable', true)) { ?>
            <?= $this->partial('publishable') ?>
        <?php } ?>
        <? // $this->partial('node') ?>
        <?php if ($req->user->isDeveloper()) { ?>
            <?= $this->partial('developer') ?>
        <?php } ?>
	</div>
	<fieldset class="footer">
		<?= $this->partial('actions-primary') ?>
		<?= $this->partial('actions-secondary') ?>
	</fieldset>
</div>