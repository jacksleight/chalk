<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Doctrine\DBAL\Logging\SQLLogger;

class ConsoleSQLLogger implements SQLLogger
{
    public function __construct()
    {
        \PhpConsole\Helper::register();
    }

    public function startQuery($sql, array $params = null, array $types = null)
    {
        \PC::debug($sql, 'sql');
        if ($params) {
            \PC::debug($params, 'params');
        }
    }

    public function stopQuery()
    {}
}
