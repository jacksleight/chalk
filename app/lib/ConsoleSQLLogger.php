<?php
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
