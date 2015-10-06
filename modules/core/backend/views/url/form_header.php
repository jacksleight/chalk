<?= $this->partial('header-before') ?>
<h1>
    <?php if (!$content->isNewMaster()) { ?>
        <?= $content->name ?>
    <?php } else { ?>
        New <?= $content->subtype == 'mailto'
            ? str_replace('URL', 'Email URL', $info->singular)
            : $info->singular ?>
    <?php } ?>
</h1>
<?= $this->partial('header-after') ?>