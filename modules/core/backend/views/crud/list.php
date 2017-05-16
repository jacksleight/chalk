<?php
$this->params([
    'bodyType'      => $bodyType      = isset($bodyType)      ? $bodyType       : 'table',
    'isAddAllowed'  => $isAddAllowed  = isset($isAddAllowed)  ? $isAddAllowed   : true,
    'isEditAllowed' => $isEditAllowed = isset($isEditAllowed) ? $isEditAllowed  : true,
]);
?>

<div class="flex-col">
    <div class="header">
        <?= $this->partial('tools') ?>
        <?= $this->partial('header') ?>
        <?php 
        $filters = $this->partial('filters');
        ?>
        <?php if (strpos($filters, '</li>') !== false) { ?>
            <div class="hanging">
                <?= $filters ?>
            </div>
        <?php } ?>
    </div>
    <div class="flex body">
        <?= $this->partial($bodyType) ?>
    </div>
    <div class="footer">
        <?= $this->partial('selection') ?>
        <?= $this->partial('pagination') ?>
    </div>
</div>