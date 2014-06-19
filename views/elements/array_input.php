<?php 
$stackable = isset($stackable) ? $stackable : true;
?>
<div class="<?= $stackable ? 'stackable' : null ?>">
    <div class="stackable-list">
        <? foreach ($entity->{$name} as $i => $item) { ?>
            <div class="stackable-item form-namevalue-horizontal">
                <? if ($stackable) { ?>
                    <input
                        type="text"
                        name="<?= "{$md['contextName']}[{$i}][name]" ?>"
                        id="<?= "_{$md['contextName']}[{$i}][name]" ?>"
                        placeholder="Name"
                        value="<?= $this->escape($item['name']) ?>"
                        <?= isset($datalist) ? "list=\"_{$md['contextName']}_datalist\"" : null ?>
                        <?= isset($disabled) && $disabled ? "disabled" : null ?>
                        <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
                <? } else { ?>
                    <span class="value disabled">
                        <?= ucwords(\Coast\str_camel_split($item['name'])) ?>
                    </span>
                    <input
                        type="hidden"
                        name="<?= "{$md['contextName']}[{$i}][name]" ?>"
                        value="<?= $this->escape($item['name']) ?>">
                <? } ?>
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
    <? if ($stackable) { ?>
        <span class="btn stackable-button">
            <i class="fa fa-plus"></i>
            Add Item
        </span>
        <script type="x-tmpl-mustache" class="stackable-template">
             <div class="stackable-item form-namevalue-horizontal">
                <input
                    type="text"
                    name="<?= "{$md['contextName']}[{{i}}][name]" ?>"
                    id="<?= "_{$md['contextName']}[{{i}}][name]" ?>"
                    placeholder="Name"
                    value=""
                    <?= isset($datalist) ? "list=\"_{$md['contextName']}_datalist\"" : null ?>
                    <?= (isset($disabled) && $disabled) || !$stackable ? "disabled" : null ?>
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
    <? } ?>
</div>
<? if (isset($datalist)) { ?>
    <datalist id="<?= "_{$md['contextName']}_datalist" ?>">
        <? foreach ($datalist as $value) { ?>
            <option value="<?= $value ?>">
        <? } ?>
    </datalist>
<? } ?>