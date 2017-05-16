<?= $this->partial('general-before') ?>	
<fieldset class="form-block">
    <div class="form-items">
        <?= $this->partial('general-top') ?>
        <?= $this->partial('general-bottom') ?>
        <?= $this->render('/element/expandable', [
            'content'       => $this->partial('general-advanced'),
            'buttonLabel'   => 'Advanced',
        ], 'core') ?>   
    </div>
</fieldset>
<?= $this->partial('general-after') ?>