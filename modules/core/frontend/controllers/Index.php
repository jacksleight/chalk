<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Frontend\Controller;

use Chalk\App as Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;

class Index extends Action
{
	public function robots(Request $req, Response $res)
	{
        $lines = isset($this->chalk->config->robotsTxt)
            ? $this->chalk->config->robotsTxt
            : ['User-agent: *', 'Disallow:'];
        
        $robots = $this->hook->fire('core_robots', (object) [
            'lines' => $lines,
        ]);

        return $res->text(implode("\n", $robots->lines));
    }
}