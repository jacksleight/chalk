<?php 
$items = $entity->{$name};
?>
<div class="stackable">
    <div class="stackable-list">
        <? foreach ($items as $i => $item) { ?>
            <div class="stackable-item">
                <input
                    type="text"
                    name="<?= "{$md['contextName']}[{$i}][name]" ?>"
                    id="<?= "_{$md['contextName']}[{$i}][name]" ?>"
                    list="<?= "_{$md['contextName']}_list" ?>"
                    placeholder="Name"
                    value="<?= $this->escape($item['name']) ?>"
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
    <script type="x-tmpl-mustache" class="stackable-template">
         <div class="stackable-item">
            <input
                type="text"
                name="<?= "{$md['contextName']}[{{i}}][name]" ?>"
                id="<?= "_{$md['contextName']}[{{i}}][name]" ?>"
                list="<?= "_{$md['contextName']}_list" ?>"
                placeholder="Name"
                value=""
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
<datalist id="<?= "_{$md['contextName']}_list" ?>">
    <option value="application-name">
    <option value="author">
    <option value="bingbot">
    <option value="copyright">
    <option value="description">
    <option value="fb:admins">
    <option value="generator">
    <option value="google-site-verification">
    <option value="googlebot">
    <option value="keywords">
    <option value="language">
    <option value="msvalidate.01">
    <option value="og:description">
    <option value="og:image">
    <option value="og:title">
    <option value="og:type">
    <option value="p:domain_verify">
    <option value="robots">
    <option value="twitter:card">
    <option value="twitter:description">
    <option value="twitter:image">
    <option value="twitter:title">
    <option value="twitter:url">
</datalist>