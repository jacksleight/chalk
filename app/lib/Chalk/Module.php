<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Chalk;

interface Module
{
    public function init(Chalk $chalk);

    public function libDir();

    public function viewDir();

    public function controllerNamespace();
}