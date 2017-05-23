<ul class="toolbar toolbar-right">
    <?= $this->partial('tools-top') ?>
    <?php if (array_intersect(['create'], $actions)) { ?>
        <li><a href="<?= $this->url([
                'action'  => 'update',
                'content' => null,
            ]) . $this->url->query([
                'tagsList' => $model->tagsList,
            ]) ?>" class="btn btn-focus icon-add">
                New <?= $info->singular ?>
        </a></li>
    <?php } ?>
    <?= $this->partial('tools-bottom') ?>
</ul>