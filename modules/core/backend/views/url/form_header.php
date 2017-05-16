<?= $this->partial('header-before') ?>
<h1>
    <?php if (!$entity->isNew()) { ?>
        <?= $entity->name ?>
    <?php } else { ?>
        New <?= $entity->subtype == 'mailto'
            ? str_replace('URL', 'Email URL', $info->singular)
            : $info->singular ?>
    <?php } ?>
</h1>
<?= $this->partial('header-after') ?>