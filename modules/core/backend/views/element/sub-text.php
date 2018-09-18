<?php
$icons = [
    'route'    => 'file',
    'fragment' => 'bookmark',
];
?>
<?php if (isset($icon) && $icon) { ?>
    <i class="icon-<?= isset($sub['icon']) ? $sub['icon'] : $icons[$sub['type']] ?>"></i>
<? } ?>
<?= $sub['name'] ?>