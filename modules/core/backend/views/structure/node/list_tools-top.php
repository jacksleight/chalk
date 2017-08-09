<?php if (array_intersect(['update'], $actions)) { ?>
    <li><a href="<?= $this->url([
        'action' => 'organise',
    ]) ?>" rel="modal" class="btn btn-focus btn-out icon-move">
        Organise
    </a></li>
<?php } ?>