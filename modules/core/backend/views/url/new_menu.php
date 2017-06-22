<li><a href="<?= $this->url([
    'action' => 'update',
    'id'     => null,
]) . $this->url->query([
    'tagsList' => $model->tagsList,
    'url'      => 'mailto:',
], true) ?>" class="item icon-envelop">
    New <?= str_replace('Link', 'Email Link', $info->singular) ?>
</a></li>
<? $this->parent() ?>