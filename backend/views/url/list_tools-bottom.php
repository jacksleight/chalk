<li><a href="<?= $this->url([
    'action' => 'edit',
]) . $this->url->query([
    'url' => 'mailto:',
], true) ?>" class="btn btn-focus icon-add">
    New <?= str_replace('External', 'Email', $info->singular) ?>
</a></li>