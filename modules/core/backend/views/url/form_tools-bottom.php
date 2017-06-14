<?php if (!$entity->isNew()) { ?>
    <?php if (!isset($node)) { ?>
        <li><a href="<?= $this->url([
            'action'    => 'update',
            'id'        => null,
        ]) . $this->url->query([
            'url' => 'mailto:',
        ], true) ?>" class="btn btn-focus btn-out icon-add">
            New <?= str_replace('Link', 'Email Link', $info->singular) ?>
        </a></li>
    <?php } ?>
<?php } ?>