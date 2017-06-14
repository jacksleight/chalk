<ul class="toolbar toolbar-right">
    <?= $this->partial('tools-top') ?>
    <?php if (array_intersect(['create'], $actions)) { ?>
        <li>
            <div class="dropdown">
                <div class="form-group form-group-horizontal">
                    <a href="<?= $this->url([
                            'action'  => 'update',
                            'content' => null,
                        ]) . $this->url->query([
                            'tagsList' => $model->tagsList,
                        ]) ?>" class="btn btn-focus icon-add">
                            New <?= $info->singular ?>
                    </a>
                    <div class="btn btn-focus dropdown-toggle dropdown-solo"></div>
                </div>
                <nav class="menu">
                    <ul>
                        <li><a href="<?= $this->url([
                            'action' => 'update',
                        ]) . $this->url->query([
                            'url' => 'mailto:',
                        ], true) ?>" class="item icon-add">
                            New <?= str_replace('Link', 'Email Link', $info->singular) ?>
                        </a></li>
                    </ul>
                </nav>
            </div>
        </li>
    <?php } ?>
    <?= $this->partial('tools-bottom') ?>
</ul>