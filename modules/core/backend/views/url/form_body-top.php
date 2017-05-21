<?= $this->parent() ?>
<?php if ($entity->subtype == 'MAILTO') { ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $entity,
        'name'          => 'mailtoEmailAddress',
        'label'         => 'Email Address',
    ), 'core') ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $entity,
        'name'          => 'mailtoSubject',
        'label'         => 'Subject',
    ), 'core') ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $entity,
        'name'          => 'mailtoBody',
        'label'         => 'Body',
        'rows'          => 6,
    ), 'core') ?>    
<?php } else { ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $entity,
        'name'          => 'url',
        'label'         => 'URL',
        'placeholder'   => 'http://example.com/',
    ), 'core') ?>
    <?php if (isset($entity->url) && $entity->url->isHttp()) { ?>
        <?= $this->render('/element/form-item', array(
            'entity'        => $entity,
            'name'          => 'urlCanonical',
            'label'         => 'Canonical URL',
            'type'          => 'input_pseudo',
            'readOnly'      => true,
        ), 'core') ?>
    <?php } ?>
<?php } ?>