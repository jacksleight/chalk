<?php if (!$content->isNew()) { ?>
    <?php if (!isset($node)) { ?>
        <li><a href="<?= $this->url([
            'entity'    => $info->name,
            'action'    => 'edit',
        ], 'core_content', true) . $this->url->query([
            'url' => 'mailto:',
        ], true) ?>" class="btn btn-focus btn-out icon-add">
            New <?= str_replace('URL', 'Email URL', $info->singular) ?>
        </a></li>
    <?php } ?>
<?php } ?>