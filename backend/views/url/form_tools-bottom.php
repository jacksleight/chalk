<?php if (!$content->isNewMaster()) { ?>
    <li><a href="<?= $this->url([
        'entity'    => $info->name,
        'action'    => 'edit',
    ], 'core_content', true) . $this->url->query([
        'url' => 'mailto:',
    ], true) ?>" class="btn btn-focus btn-out icon-add">
        New <?= str_replace('External', 'Email', $info->singular) ?>
    </a></li>
<?php } ?>