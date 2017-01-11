<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core,
	Chalk\Core\Content,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @HasLifecycleCallbacks
*/
class Block extends Content
{
	public static $chalkSingular = 'Block';
	public static $chalkPlural   = 'Blocks';
    public static $chalkIcon     = 'box';

    /**
     * @Column(type="text", nullable=true)
     */
	protected $body;
}