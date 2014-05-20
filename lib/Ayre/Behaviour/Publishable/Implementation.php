<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Publishable;

use Ayre;

trait Implementation
{
	/**
     * @Column(type="string", length=10)
     */
	protected $status = Ayre::STATUS_PUBLISHED;
}