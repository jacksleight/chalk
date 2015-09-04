<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Core,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Blank extends Content
{
	public static $chalkSingular = 'Placeholder';
	public static $chalkPlural   = 'Placeholders';
    public static $chalkIcon     = 'blank';
	public static $chalkIsNode   = true;
	public static $chalkIsUrl    = true;
}