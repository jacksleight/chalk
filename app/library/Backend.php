<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Chalk\Chalk;
use Chalk\Event;
use Chalk\Module;
use Closure;
use Coast\Request;
use Coast\Response;

class Backend extends \Coast\App
{
    const FORMAT_DATE = 'jS F Y';

    public function preExecute(Request $req = null, Response $res = null)
    {
        Chalk::isFrontend(false);
    }

    public function postExecute(Request $req = null, Response $res = null)
    {
        Chalk::isFrontend(true);
    }

    public function layouts()
    {
        if (!isset($this->chalk->config->layoutDir) || !$this->chalk->config->layoutDir->exists()) {
            return [];
        }

        $layouts = [];
        $it = $this->chalk->config->layoutDir->iterator(null, true);
        foreach ($it as $file) {
            $path   = $file->toRelative($this->chalk->config->layoutDir);
            $path   = $path->extName('');
            $name   = trim($path, './');
            $label  = ucwords(str_replace(['-', '/', '_'], [' ', ' – ', ' – '], $name));
            $layouts[$name] = $label;
        }
        unset($layouts['default']);
        ksort($layouts);
        return $layouts;
    }

    public function statusClass($status)
    {
        return [
            Chalk::STATUS_DRAFT      => 'negative',
            Chalk::STATUS_PENDING    => 'negative',
            Chalk::STATUS_PUBLISHED  => 'positive badge-out',
            Chalk::STATUS_ARCHIVED   => 'light badge-out',
        ][$status];
    }
}