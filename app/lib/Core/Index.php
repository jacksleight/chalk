<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Chalk,
    Chalk\Core,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(options={"engine"="MyISAM"}, indexes={@Index(columns={"content"}, flags={"fulltext"})})
*/
class Index extends \Toast\Entity
{
	/**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
	protected $id;
    
    protected $entityObject;

    /**
     * @Column(type="string")
     */
    protected $entityType;

    /**
     * @Column(type="integer")
     */
    protected $entityId;

	/**
     * @Column(type="text")
     */
	protected $content;

    public function __construct(\Toast\Entity $entityObject)
    {
        $this->entityObject = $entityObject;
        $this->entityType   = Chalk::info($this->entityObject)->name;
        $this->entityId     = $this->entityObject->id;
    }

    public function reindex()
    {
        $content = implode(' ', $this->entityObject->searchableContent);
        $content = strip_tags($content);
        $content = html_entity_decode($content, ENT_COMPAT | ENT_HTML5, 'utf-8');
        $content = mb_strtolower($content, 'utf-8');
        $content = preg_replace("/['’]/u", '', $content);
        $content = preg_replace("/[^[:alnum:]]+/u", ' ', $content);
        $content = trim($content);
        $this->content = $content;
    }
}