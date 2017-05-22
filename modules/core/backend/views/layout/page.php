<? $this->start() ?>
    <div class="flex flex-row bottombar">
        <div class="flex-col leftbar">
            <div class="flex">
                <div class="body">
                    <nav class="nav" role="navigation">
                        <?php
                        if ($model->mode == 'select') {
                            $items = $this->select->children('root');
                        } else {
                            $items = $this->nav->children($this->nav->main()['name']);
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
<? $html = $this->end() ?>
<?php
if (isset($model->mode) && $model->mode == 'select') {
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