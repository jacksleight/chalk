<fieldset class="form-block">
    <div class="form-items">
        <?= $this->render('/element/form-item', array(
            'entity'        => $entity,
            'name'          => 'name',
            'label'         => 'Label',
            'placeholder'   => $entity->name,
            'note'          => 'Text used in navigation and URLs',
        ), 'core') ?>
        <?= $this->render('/element/form-item', array(
            'entity'        => $entity,
            'name'          => 'isHidden',
            'label'         => 'Hidden',
        ), 'core') ?>
    </div>
</fieldset>