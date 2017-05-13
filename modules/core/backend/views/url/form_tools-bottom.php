<?php if (!$content->isNew()) { ?>
    <li><a href="<?= $this->url([
        'action'    => 'edit',
        'id'        => null,
    ]) . $this->url->query([
        'url' => 'mailto:',
    ], true) ?>" class="btn btn-focus btn-out icon-add">
        New <?= str_replace('URL', 'Email URL', $info->singular) ?>
    </a></li>
<?php } ?>