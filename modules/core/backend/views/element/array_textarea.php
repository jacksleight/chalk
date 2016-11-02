<?php
$items = [];
?>
<?php foreach ($value as $i => $item) { ?>
    <?php $this->start() ?>
        <div class="form-group form-group-vertical">
            <?php if ($stackable) { ?>
                <?= $this->render('input_text', [
                    'name'        => "{$name}[{$i}][name]",
                    'id'          => "{$id}[{$i}][name]",
                    'placeholder' => 'Name',
                    'value'       => $item['name'],
                    'disabled'    => isset($disabled) ? $disabled : null,
                    'readOnly'    => isset($readOnly) ? $readOnly : null,
                ]) ?>
            <?php } else { ?>
                <?= $this->render('input_pseudo', [
                    'name'     => "{$name}[{$i}][name]",
                    'value'    => ucwords(\Coast\str_camel_split($item['name'])),
                    'disabled' => true,
                ]) ?>
                <?= $this->render('input_hidden', [
                    'name'  => "{$name}[{$i}][name]",
                    'id'    => "{$id}[{$i}][name]",
                    'value' => $item['name'],
                ]) ?>
            <?php } ?>
            <?= $this->render('textarea', [
                'name'     => "{$name}[{$i}][value]",
                'id'       => "{$id}[{$i}][value]",
                'value'    => $item['value'],
                'class'    => isset($class) ? $class : null,
                'rows'     => isset($rows) ? $rows : null,
                'cols'     => isset($cols) ? $cols : null,
                'disabled' => isset($disabled) ? $disabled : null,
                'readOnly' => isset($readOnly) ? $readOnly : null,
            ]) ?>
        </div>  
    <?php $items[] = $this->end() ?>
<?php } ?>
<?php if (isset($stackable) ? $stackable : true) { ?>
    <?php $this->start() ?>
        <div class="form-group form-group-vertical">
            <?= $this->render('input_text', [
                'name'        => "{$name}[{{i}}][name]",
                'id'          => "{$id}[{{i}}][name]",
                'placeholder' => 'Name',
                'disabled'    => isset($disabled) ? $disabled : null,
                'readOnly'    => isset($readOnly) ? $readOnly : null,
            ]) ?>
            <?= $this->render('textarea', [
                'name'     => "{$name}[{{i}}][value]",
                'id'       => "{$id}[{{i}}][value]",
                'class'    => isset($class) ? $class : null,
                'rows'     => isset($rows) ? $rows : null,
                'cols'     => isset($cols) ? $cols : null,
                'disabled' => isset($disabled) ? $disabled : null,
                'readOnly' => isset($readOnly) ? $readOnly : null,
            ]) ?>
        </div>  
    <?php $template = $this->end() ?>
    <?= $this->inner('stackable', [
        'items'     => $items,
        'template'  => $template,
    ]) ?>
<?php } else { ?>
    <?= implode(null, $items) ?>
<?php } ?>