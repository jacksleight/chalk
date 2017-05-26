<?php $this->start() ?>
    <div class="flex bottombar">
        <div class="body">
            <?= $this->content('main') ?>
        </div>
    </div>
<?php $html = $this->end() ?>
<?php
if ($req->isAjax()) {
    echo $this->content('main');
} else {
    $this->outer("layout/body", [], 'core');
    $this->block('main');
    echo $html;
}