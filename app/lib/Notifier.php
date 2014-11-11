<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
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
	
	public function notifications()
	{
		$session =& $this->req->session('chalk');
        if (!isset($session->notifications)) {
        	$session->notifications = [];
        }
        $notifications = $session->notifications;
        $session->notifications = [];
        return $notifications;
	}
}