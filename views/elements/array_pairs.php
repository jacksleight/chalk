<?php 
$items = $entity->{$name};
?>
<div class="stackable">
    <div class="stackable-list pairs">
        <? foreach ($items as $i => $item) { ?>
            <div class="stackable-item pairs-item">
                <input
                    type="text"
                    name="<?= "{$md['contextName']}[{$i}][name]" ?>"
                    id="<?= "_{$md['contextName']}[{$i}][name]" ?>"
                    placeholder="Name"
                    value="<?= $this->escape($item['name']) ?>"
                    <?= isset($datalist) ? "list=\"_{$md['contextName']}_datalist\"" : null ?>
                    <?= isset($disabled) && $disabled ? "disabled" : null ?>
                    <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
                <input
                    type="text"
                    name="<?= "{$md['contextName']}[{$i}][value]" ?>"
                    id="<?= "_{$md['contextName']}[{$i}][value]" ?>"
                    placeholder="Value"
                    value="<?= $this->escape($item['value']) ?>"
                    <?= isset($disabled) && $disabled ? "disabled" : null ?>
                    <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
            </div>
        <? } ?>
    </div>
    <span class="btn stackable-button">
        <i class="fa fa-plus"></i>
        Add
    </span>
    <script type="x-tmpl-mustache" class="stackable-template">
         <div class="stackable-item pairs-item">
            <input
                type="text"
                name="<?= "{$md['contextName']}[{{i}}][name]" ?>"
                id="<?= "_{$md['contextName']}[{{i}}][name]" ?>"
                placeholder="Name"
                value=""
                <?= isset($datalist) ? "list=\"_{$md['contextName']}_datalist\"" : null ?>
                <?= isset($disabled) && $disabled ? "disabled" : null ?>
                <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
            <input
                type="text"
                name="<?= "{$md['contextName']}[{{i}}][value]" ?>"
                id="<?= "_{$md['contextName']}[{{i}}][value]" ?>"
                placeholder="Value"
                value=""
                <?= isset($disabled) && $disabled ? "disabled" : null ?>
                <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
        </div>
    </script>
</div>
<? if (isset($datalist)) { ?>
    <datalist id="<?= "_{$md['contextName']}_datalist" ?>">
        <? foreach ($datalist as $value) { ?>
            <option value="<?= $value ?>">
        <? } ?>
    </datalist>
<? } ?>