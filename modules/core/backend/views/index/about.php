<?php if (!$req->isAjax()) { ?>
    <?php $this->outer('/layout/body_simple') ?>
    <?php $this->block('main') ?>
<?php } ?>

<div class="flex-col" data-modal-size="500x500">
    <div class="header">
        <h1>
            Chalk
            <small><?= Chalk\Chalk::VERSION ?></small>
        </h1>
    </div>
    <div class="body flex">
        <h2>The MIT License</h2>
        <p>Copyright (c) 2015 Jack Sleight &ndash; <a target="_blank" href="http://jacksleight.com/">http://jacksleight.com/</a></p>
        <p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</p>
        <p>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</p>
        <p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p>
    </div>
</div>