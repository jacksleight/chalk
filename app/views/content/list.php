<?php
$isNewAllowed  = isset($isNewAllowed) ? $isNewAllowed : true;
$isEditAllowed = isset($isEditAllowed) ? $isEditAllowed : true;
$isUploadable  = is_a($info->class, 'Chalk\Core\File', true);
$bodyType      = isset($bodyType)   ? $bodyType   : 'table';
$bodyClass     = isset($bodyClass)  ? $bodyClass  : null;
?>
<? $this->block() ?>

<? if ($isUploadable) { ?>
    <div class="uploadable">
<? } ?>

<div class="flex-col">
<div class="flex body">

<? if ($this->block('tools')) { ?>

<ul class="toolbar toolbar-right">
    <?= $this->content('tools-top') ?>
    <? if ($isUploadable) { ?>
        <ul class="toolbar">
            <li><span class="btn btn-focus icon-upload uploadable-button">
                Upload <?= $info->plural ?>
            </span></li>
        </ul>
    <? } else if ($isNewAllowed) { ?>
        <li><a href="<?= $this->url([
                'action' => 'edit',
            ]) ?>" class="btn btn-focus icon-add">
                New <?= $info->singular ?>
        </a></li>
    <? } ?>
    <?= $this->content('tools-bottom') ?>
</ul>
    
<? } if ($this->block('header')) { ?>

<h1>
    <? if (isset($headerPrefix)) { ?>
        <?= $headerPrefix ?>
    <? } ?>
    <?= $info->plural ?>
</h1>
    
<? } if ($this->block('filters')) { ?>

<ul class="toolbar toolbar-flush autosubmitable">
    <li class="flex-3">
        <?= $this->render('/element/form-input', array(
            'type'          => 'input_search',
            'entity'        => $index,
            'name'          => 'search',
            'autofocus'     => true,
            'placeholder'   => 'Search…',
        )) ?>
    </li>
    <?= $this->content('filters-top') ?>
    <?php
    $subtypes = $this->em($info)->subtypes();
    $values   = [];
    $class    = $info->class;
    foreach ($subtypes as $subtype) {
        $values[$subtype['subtype']] = $class::staticSubtypeLabel($subtype['subtype']);
    }  
    asort($values);
    ?>
    <? if ($subtypes) { ?>
        <li class="flex-2">
            <?= $this->render('/element/form-input', array(
                'type'          => 'dropdown_multiple',
                'entity'        => $index,
                'name'          => 'subtypes',
                'icon'          => '',
                'placeholder'   => 'Type',
                'values'        => $values,
            )) ?>
        </li>
    <? } ?>
    <li class="flex-2">
        <?= $this->render('/element/form-input', array(
            'type'          => 'dropdown_single',
            'entity'        => $index,
            'null'          => 'Any',
            'name'          => 'modifyDateMin',
            'icon'          => 'icon-updated-dark',
            'placeholder'   => 'Updated',
        )) ?>
    </li>
    <li class="flex-2">
        <?= $this->render('/element/form-input', array(
            'type'          => 'dropdown_multiple',
            'entity'        => $index,
            'name'          => 'statuses',
            'icon'          => 'icon-status-dark',
            'placeholder'   => 'Status',
        )) ?>
    </li>
    <?= $this->content('filters-bottom') ?>
</ul>
    
<? } if ($this->block('body')) { ?>

<? if ($isUploadable) { ?>

    <div class="multiselectable">
        <?= $this->render('/element/form-input', [
            'type'   => 'input_hidden',
            'entity' => $index,
            'name'   => 'contentIds',
            'class'  => 'multiselectable-values',
        ]) ?>
        <ul class="thumbs <?= $isUploadable ? 'uploadable-list' : null ?>">
            <?php if (count($contents)) { ?>
                <?php foreach ($contents as $content) { ?>
                    <li><?= $this->child('thumb', ['content' => $content]) ?></li>
                <?php } ?>
            <?php } else { ?>
                <li></li>
            <?php } ?>      
        </ul>
        <?php if (!count($contents)) { ?>
            <div class="notice">
                No <?= strtolower($info->plural) ?> found
            </div>
        <?php } ?>  
    </div>
    
<? } else { ?>
    
    <table class="multiselectable">
        <colgroup>
            <col class="col-select">
            <col class="">
            <col class="col-right col-contract">
            <col class="col-right col-badge">
        </colgroup>
        <thead>
            <tr>
                <th scope="col" class="col-select">
                    <input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
                    <?= $this->render('/element/form-input', [
                        'type'   => 'input_hidden',
                        'entity' => $index,
                        'name'   => 'contentIds',
                        'class'  => 'multiselectable-values',
                    ]) ?>
                </th>
                <th scope="col" class="">Name</th>
                <th scope="col" class="col-right col-contract">Updated</th>
                <th scope="col" class="col-right col-badge">Status</th>
            </tr>
        </thead>
        <tbody class="<?= $isUploadable ? 'uploadable-list' : null ?>">
            <?php if (count($contents)) { ?>
                <?php foreach ($contents as $content) { ?>
                    <tr class="selectable <?= $isEditAllowed ? 'clickable' : null ?>">
                        <td class="col-select">
                            <?= $this->render('/behaviour/selectable/checkbox', [
                                'entity'   => $content,
                                'entities' => $index->contents,
                            ]) ?>
                        </td>
                        <th class="" scope="row">
                            <? if ($isEditAllowed) { ?>
                                <a href="<?= $this->url([
                                    'action'    => 'edit',
                                    'content'   => $content->id,
                                ]) ?>"><? } ?><?= $content->name ?><? if ($isEditAllowed) { ?></a>
                            <? } ?>
                            <br>
                            <small><?= $content->clarifier($info->class != 'Chalk\Core\Content') ?></small>
                        </th>
                        <td class="col-right col-contract">
                            <?= $content->modifyDate->diffForHumans() ?>
                            <small>by <?= $content->modifyUserName ?></small>
                        </td>   
                        <td class="col-right col-badge">
                            <span class="badge badge-upper badge-<?= $this->app->statusClass($content->status) ?>"><?= $content->status ?></span>
                        </td>   
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td class="notice" colspan="4">
                        No <?= strtolower($info->plural) ?> found
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<? } ?>

<? } $this->block() ?>

</div>
<div class="footer">

<? if ($this->block('pagination')) { ?>

<ul class="toolbar toolbar-right autosubmitable">
    <li>
        Show&nbsp;
        <?= $this->render('/element/form-input', array(
            'type'   => 'select',
            'entity' => $index,
            'name'   => 'limit',
            'null'   => 'All',
        )) ?>
    </li>
    <li>
        &nbsp;
        Selected&nbsp;
        <?= $this->render('/element/form-input', array(
            'type'   => 'select',
            'entity' => $index,
            'name'   => 'action',
            'null'   => 'Action',
        )) ?>
    </li>
</ul>
<?= $this->render('/element/form-input', [
    'type'      => 'paginator',
    'entity'    => $index,
    'name'      => 'page',
    'limit'     => $index->limit,
    'count'     => $contents->count(),
]) ?>
    
<? } $this->block() ?>

</div>
</div>

<? if ($isUploadable) { ?>
    <input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
    <script type="x-tmpl-mustache" class="uploadable-template">
        <?= $this->child('/content/thumb', ['template' => true]) ?>
    </script>
    </div>
<? } ?>
