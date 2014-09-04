<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core;

use Ayre\Core,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Alias extends Content
{
	public static $info = [
		'singular'	=> 'Alias',
		'plural'	=> 'Aliases',
	];

    /**
     * @ManyToOne(targetEntity="\Ayre\Core\Content")
     * @JoinColumn(nullable=true)
     */
    protected $content;
}