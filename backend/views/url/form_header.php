<?= $this->partial('header-before') ?>
<h1>
    <?php if (!$content->isNewMaster()) { ?>
        <?= $content->name ?>
    <?php } else { ?>
        New <?= $content->subtype == 'mailto'
            ? str_replace('Link', 'Email Link', $info->singular)
            : $info->singular ?>
    <?php } ?>
</h1>
<?= $this->partial('header-after') ?>