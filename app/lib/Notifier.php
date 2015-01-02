<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

class Notifier implements \Coast\App\Access
{
    use \Coast\App\Access\Implementation;

	public function __invoke($text, $type = null)
	{
		$session =& $this->req->session('chalk');
        if (!isset($session->notifications)) {
        	$session->notifications = [];
        }
		$session->notifications[] = [$text, $type];
	}
	
	public function notifications($clear = true)
	{
		$session =& $this->req->session('chalk');
        if (!isset($session->notifications)) {
        	$session->notifications = [];
        }
        $notifications = $session->notifications;
        if ($clear) {
        	$session->notifications = [];
        }
        return $notifications;
	}
}