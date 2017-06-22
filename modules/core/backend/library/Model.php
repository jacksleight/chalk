<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend;

use Chalk\Chalk;
use Chalk\Core;
use Chalk\Model as ChalkModel;

class Model extends ChalkModel
{
    protected $mode;

    protected $filters;
    protected $filtersList;
    protected $filtersInfo;

    protected $selected;
    protected $selectedList;
    protected $selectedType;

    protected $tags;
    protected $tagsList;

    protected $remembers;
    protected $remembersList;

    protected $redirect;

    protected static function _defineMetadata($class)
    {
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
            'fields' => array(
                'mode' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
                'filters' => array(
                    'type'      => 'array',
                    'nullable'  => true,
                ),
                'filtersList' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
                'filtersInfo' => array(
                    'type'      => 'object',
                    'nullable'  => true,
                ),
                'selected' => array(
                    'type'      => 'array',
                    'nullable'  => true,
                ),
                'selectedList' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
                'selectedType' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
                'tags' => array(
                    'type'      => 'array',
                    'nullable'  => true,
                ),
                'tagsList' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
                'remembers' => array(
                    'type'      => 'array',
                    'nullable'  => true,
                ),
                'remembersList' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
                'redirect' => array(
                    'type'      => 'coast_url',
                    'nullable'  => true,
                ),
            ),
        ));
    }

    public function filters($filters = null)
    {
        if (func_num_args() > 0) {
            $this->filtersList = \Chalk\filters_list_build($filters);
            return $this;
        }
        return \Chalk\filters_list_parse($this->filtersList);
    }

    public function selected(array $selected = null)
    {
        if (func_num_args() > 0) {
            $this->selectedList = implode('.', $selected);
            return $this;
        }
        return isset($this->selectedList)
            ? explode('.', $this->selectedList)
            : [];
    }

    public function tags(array $tags = null)
    {
        if (func_num_args() > 0) {
            $this->tagsList = implode('.', $tags);
            return $this;
        }
        return isset($this->tagsList)
            ? explode('.', $this->tagsList)
            : [];
    }

    public function tagsHas($tag)
    {
        $tags = $this->tags();
        return in_array($tag, $tags);
    }

    public function tagsPlus($tag)
    {
        $tags = $this->tags();
        $tags = array_merge($tags, [$tag]);
        return $tags;
    }

    public function tagsMinus($tag)
    {
        $tags = $this->tags();
        unset($tags[array_search($tag, $tags)]);
        return $tags;
    }

    public function tagsToggle($tag)
    {
        if ($tag == 'none') {
            return $this->tagsHas($tag)
                ? []
                : [$tag];
        }
        $tags = $this->tagsHas($tag)
            ? $this->tagsMinus($tag)
            : $this->tagsPlus($tag);
        $none = array_search('none', $tags);
        if ($none !== false) {
            unset($tags[$none]);
        }
        return $tags;
    }

    public function tagsListPlus($tag)
    {
        return implode('.', $this->tagsPlus($tag));
    }

    public function tagsListMinus($tag)
    {
        return implode('.', $this->tagsMinus($tag));
    }

    public function tagsListToggle($tag)
    {
        return implode('.', $this->tagsToggle($tag));
    }

    public function remembers(array $remembers = [])
    {
        return $remembers;
    }

    public function remembersList()
    {
        if (func_num_args() > 0) {
            return $this;
        }
        return implode('.', $this->remembers());
    }
}