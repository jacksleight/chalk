<li>
    <?php
    $quick = $this->em->wrap(new Chalk\Core\Backend\Model\Url\Quick());
    ?>
    <div class="form-group form-group-horizontal" style="width: 320px;">
        <?= $this->render('/element/form-input', array(
            'entity'        => $quick,
            'type'          => 'input_url',
            'name'          => 'url',
            'placeholder'   => 'http://example.com/',
        ), 'core') ?>
        <button class="btn btn-focus icon-plus width-3" formmethod="post" formaction="<?= $this->url->route([
            'action' => 'quick',
        ]) . $this->url->query([
            'redirect' => $this->url([]),
        ]) ?>">
            Add
        </button>
    </div>
</li>