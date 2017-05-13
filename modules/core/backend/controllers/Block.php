<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\App as Chalk;
use Coast\Request;
use Coast\Response;

class Block extends Content
{
	protected $_entityClass = 'Chalk\Core\Block';
}