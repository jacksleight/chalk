<? if ($isUploadable) { ?>

    <div class="multiselectable">
        <?= $this->render('/element/form-input', [
            'type'   => 'input_hidden',
            'entity' => $index,
            'name'   => 'contentIds',
            'class'  => 'multiselectable-values',
        ]) ?>
        <ul class="thumbs <?= $isUploadable ? 'uploadable-list' : null ?>">
            <?php foreach ($contents as $content) { ?>
                <li class="thumbs_i"><?= $this->inner('thumb', ['content' => $content]) ?></li>
            <?php } ?>     
            <?= str_repeat('<li></li>', 10) ?>
        </ul>
        <?php if (!count($contents)) { ?>
            <div class="notice">
                <h2>No <?= $info->plural ?> Found</h2>
                <?php if ($isNewAllowed) { ?>
                    <p>To create a new <?= strtolower($info->singular) ?> click the 'New <?= $info->singular ?>' button above.</p>
                <?php } ?>
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
                        <h2>No <?= $info->plural ?> Found</h2>
                        <?php if ($isNewAllowed) { ?>
                            <p>To create a new <?= strtolower($info->singular) ?> click the 'New <?= $info->singular ?>' button above.</p>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<? } ?>