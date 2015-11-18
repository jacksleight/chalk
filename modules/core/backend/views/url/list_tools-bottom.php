<?php if ($isAddAllowed) { ?>
    <li><a href="<?= $this->url([
        'action' => 'edit',
    ]) . $this->url->query([
        'url' => 'mailto:',
    ], true) ?>" class="btn btn-focus icon-add">
        New <?= str_replace('URL', 'Email URL', $info->singular) ?>
    </a></li>
<?php } ?>