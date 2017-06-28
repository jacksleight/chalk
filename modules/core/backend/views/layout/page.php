<?php $this->start() ?>
    <div class="flex flex-row bottombar">
        <div class="flex-col leftbar">
            <div class="flex">
                <div class="body">
                    <nav class="nav" role="navigation">
                        <?php
                        if (array_intersect(['select-one', 'select-all'], [$model->mode])) {
                            $select = $this->hook->fire('core_select', new Chalk\Nav(
                                $req,
                                $this->url,
                                $this->user,
                                $this->model->filtersInfo
                            ));
                            $items = $select->children('root', 1);
                        } else {
                            $items = $this->nav->children($this->nav->main()['name'], 1);
                        }
                        ?>
                        <?= $this->inner('nav', [
                            'items' => $items,
                        ]) ?>
                    </nav>
                </div>
            </div>
        </div>
        <div class="flex flex-col rightbar">
            <div class="flex">
                <?= $this->content('main') ?>
            </div>
        </div>
    </div>
<?php $html = $this->end() ?>
<?php
if (array_intersect(['select-one', 'select-all'], [$model->mode])) {
    if ($req->isAjax()) {
        echo $html;
    } else {
        $this->outer("layout/body", [], 'core');
        $this->block('main');
        echo $html;
    }
} else {
    if ($req->isAjax()) {
        echo $this->content('main');
    } else {
        $this->outer("layout/body", [], 'core');
        $this->block('main');
        echo $html;
    }
}