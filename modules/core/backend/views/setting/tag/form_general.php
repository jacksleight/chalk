<fieldset class="form-block">
    <div class="form-items">
        <?= $this->render('/element/form-item', array(
            'entity'    => $entity,
            'name'      => 'name',
            'label'     => 'Name',
            'autofocus' => true,
        ), 'core') ?>
    </div>
</fieldset>