<?php if ($isNewAllowed) { ?>
    <li><a href="<?= $this->url([
        'action' => 'edit',
    ]) . $this->url->query([
        'url' => 'mailto:',
    ], true) ?>" class="btn btn-focus icon-add">
        New <?= str_replace('Link', 'Email Link', $info->singular) ?>
    </a></li>
<?php } ?>