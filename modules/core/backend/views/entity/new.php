<?php
$menu = $this->partial('menu');
?>
<div class="dropdown">
    <div class="form-group form-group-horizontal">
        <?= $this->partial('btn') ?>
        <?php if (strpos($menu, '</li>') !== false) { ?>
            <div class="btn btn-focus <?= isset($class) ? $class : null ?> dropdown-toggle dropdown-solo"></div>
        <?php } ?>
    </div>
    <?php if (strpos($menu, '</li>') !== false) { ?>
        <nav class="menu menu-right">
            <ul>
                <?= $menu ?>
            </ul>
        </nav>
    <?php } ?>
</div>