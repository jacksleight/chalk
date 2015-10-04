<?php if ($content->subtype == 'mailto') { ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $content,
        'name'          => 'mailtoEmailAddress',
        'label'         => 'Email Address',
    ), 'core') ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $content,
        'name'          => 'mailtoSubject',
        'label'         => 'Subject',
    ), 'core') ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $content,
        'name'          => 'mailtoBody',
        'label'         => 'Body',
        'rows'          => 6,
    ), 'core') ?>    
<?php } else { ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $content,
        'name'          => 'url',
        'label'         => 'URL',
        'placeholder'   => 'http://example.com/',
    ), 'core') ?>
    <?php if ($content->subtype == 'http' || $content->subtype == 'https') { ?>
        <?= $this->render('/element/form-item', array(
            'entity'        => $content,
            'name'          => 'urlCanonical',
            'label'         => 'Canonical URL',
            'type'          => 'input_pseudo',
            'readOnly'      => true,
        ), 'core') ?>
    <?php } ?>
<?php } ?>