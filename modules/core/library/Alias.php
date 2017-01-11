<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Alias extends Content
{
	public static $chalkSingular = 'Alias';
	public static $chalkPlural   = 'Aliases';
    public static $chalkIcon     = 'alias';

    /**
     * @ManyToOne(targetEntity="\Chalk\Core\Content")
     * @JoinColumn(nullable=true)
     */
    protected $content;
}