<a href="<?= $this->url([
    'action' => 'update',
    'id'     => null,
]) . $this->url->query([
    'tagsList' => $model->tagsList,
], true) ?>" class="btn btn-focus <?= isset($class) ? $class : null ?> icon-add">
    New <?= $info->singular ?>
</a>