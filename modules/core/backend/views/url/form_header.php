<?= $this->partial('header-before') ?>
<h1>
    <?php if (!$entity->isNew()) { ?>
        <?= $entity->name ?>
    <?php } else { ?>
        New <?= $entity->subtype == 'MAILTO'
            ? str_replace('Link', 'Email Link', $info->singular)
            : $info->singular ?>
    <?php } ?>
</h1>
<?= $this->partial('header-after') ?>