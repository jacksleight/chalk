<ul class="toolbar toolbar-flush autosubmitable">
    <li class="flex-3">
        <?= $this->render('/element/form-input', array(
            'type'          => 'input_search',
            'entity'        => $index,
            'name'          => 'search',
            'autofocus'     => true,
            'placeholder'   => 'Search…',
        ), 'core') ?>
    </li>
    <?= $this->partial('filters-top') ?>
    <?php
    $subtypes = $this->em($info)->subtypes(['types' => isset($filters) ? $filters : null]);
    $values   = [];
    $class    = $info->class;
    foreach ($subtypes as $subtype) {
        $values[$subtype['subtype']] = $class::staticSubtypeLabel($subtype['subtype']);
    }
    asort($values);
    ?>
    <? if (count($subtypes)) { ?>
        <li class="flex-2">
            <?= $this->render('/element/form-input', array(
                'type'          => 'dropdown_multiple',
                'entity'        => $index,
                'name'          => 'subtypes',
                'icon'          => 'icon-subtype',
                'placeholder'   => 'Type',
                'values'        => $values,
            ), 'core') ?>
        </li>
    <? } ?>
    <li class="flex-2">
        <?= $this->render('/element/form-input', array(
            'type'          => 'dropdown_single',
            'entity'        => $index,
            'null'          => 'Any',
            'name'          => 'modifyDateMin',
            'icon'          => 'icon-updated',
            'placeholder'   => 'Updated',
        ), 'core') ?>
    </li>
    <li class="flex-2">
        <?= $this->render('/element/form-input', array(
            'type'          => 'dropdown_multiple',
            'entity'        => $index,
            'name'          => 'statuses',
            'icon'          => 'icon-status',
            'placeholder'   => 'Status',
        ), 'core') ?>
    </li>
    <?= $this->partial('filters-bottom') ?>
</ul>