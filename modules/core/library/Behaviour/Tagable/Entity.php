<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Tagable;

use Chalk\Chalk;
use Chalk\Core\Tag;
use Doctrine\Common\Collections\ArrayCollection;

trait Entity
{    
    /**
     * @ManyToMany(targetEntity="\Chalk\Core\Tag", inversedBy="contents", cascade={"persist"})
     */
    protected $tags;

    protected static function _tagable_defineMetadata($class)
    {
        return array(
            'fields' => array(
                'tagNamesList' => [
                    'type'     => 'string',
                    'nullable' => true,
                ],
            ),
        );
    }

    protected function _tagable_construct()
    {   
        $this->tags = new ArrayCollection();
    }

    public function tagNamesList($tagNamesList = null)
    {
        if (func_num_args() > 0) {
            if (!isset($tagNamesList)) {
                $this->tags->clear();
                return $this;
            }
            $split = explode('|', $tagNamesList);
            $names = [];
            foreach ($split as $name) {
                $names[\Chalk\str_slugify($name)] = trim($name);
            }
            $tags = \Toast\Wrapper::$em->__invoke('core_tag')->all([
                'slugs' => array_keys($names),
            ]);
            foreach ($tags as $tag) {
                if (isset($names[$tag->slug])) {
                    unset($names[$tag->slug]);
                }
            }
            foreach ($names as $slug => $name) {
                $tag = new Tag();
                $tag->fromArray([
                    'name' => $name,
                ]);
                $tags[] = $tag;
            }
            foreach ($tags as $tag) {
                if (!$this->tags->contains($tag)) {
                    $this->tags->add($tag);
                }
            }
            foreach ($this->tags as $tag) {
                if (!in_array($tag, $tags, true)) {
                    $this->tags->removeElement($tag);
                }
            }
            return $this;
        }
        return implode('|', array_map(function($tag) {
            return $tag->name;
        }, $this->tags->toArray()));
    }
}