<?php 
$stackable = isset($stackable) ? $stackable : true;
?>
<div class="<?= $stackable ? 'stackable' : null ?>">
    <div class="stackable-list">
        <?php foreach ($value as $i => $item) { ?>
            <div class="stackable-item form-group form-group-horizontal">
                <?php if ($stackable) { ?>
                    <input
                        type="text"
                        name="<?= "{$name}[{$i}][name]" ?>"
                        id="<?= "{$id}[{$i}][name]" ?>"
                        placeholder="Name"
                        value="<?= $this->escape($item['name']) ?>"
                        class="width-3"
                        <?= isset($datalist) ? "list=\"{$id}_datalist\"" : null ?>
                        <?= isset($disabled) && $disabled ? "disabled" : null ?>
                        <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
                <?php } else { ?>
                    <span class="value disabled width-3">
                        <?= ucwords(\Coast\str_camel_split($item['name'])) ?>
                    </span>
                    <input
                        type="hidden"
                        name="<?= "{$name}[{$i}][name]" ?>"
                        value="<?= $this->escape($item['name']) ?>">
                <?php } ?>
                <input
                    type="text"
                    name="<?= "{$name}[{$i}][value]" ?>"
                    id="<?= "{$id}[{$i}][value]" ?>"
                    placeholder="Value"
                    value="<?= $this->escape($item['value']) ?>"
                    <?= isset($disabled) && $disabled ? "disabled" : null ?>
                    <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
            </div>
        <?php } ?>
    </div>
    <?php if ($stackable) { ?>
        <span class="btn stackable-button icon-add">
            Add Item
        </span>
        <script type="x-tmpl-mustache" class="stackable-template">
             <div class="stackable-item form-group form-group-horizontal">
                <input
                    type="text"
                    name="<?= "{$name}[{{i}}][name]" ?>"
                    id="<?= "{$id}[{{i}}][name]" ?>"
                    placeholder="Name"
                    value=""
                    class="width-3"
                    <?= isset($datalist) ? "list=\"{$id}_datalist\"" : null ?>
                    <?= (isset($disabled) && $disabled) || !$stackable ? "disabled" : null ?>
                    <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
                <input
                    type="text"
                    name="<?= "{$name}[{{i}}][value]" ?>"
                    id="<?= "{$id}[{{i}}][value]" ?>"
                    placeholder="Value"
                    value=""
                    <?= isset($disabled) && $disabled ? "disabled" : null ?>
                    <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
            </div>
        </script>
    <?php } ?>
</div>
<?php if (isset($datalist)) { ?>
    <datalist id="<?= "{$id}_datalist" ?>">
        <?php foreach ($datalist as $value) { ?>
            <option value="<?= $value ?>">
        <?php } ?>
    </datalist>
<?php } ?>