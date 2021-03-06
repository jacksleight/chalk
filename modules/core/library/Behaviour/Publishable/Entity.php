<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Publishable;

use Chalk\Chalk;
use Chalk\Entity as ChalkEntity;

trait Entity
{
	/**
     * @Column(type="string", length=10)
     */
	protected $status = Chalk::STATUS_DRAFT;

    /**
     * @Column(type="datetime", nullable=true)
     */
	protected $publishDate;

    /**
     * @Column(type="datetime", nullable=true)
     */
	protected $archiveDate;

    protected static function _publishable_defineMetadata($class)
    {
        return array(
            'fields' => array(
                'status' => array(
                'values' => [
                        Chalk::STATUS_DRAFT     => 'Draft',
                        Chalk::STATUS_PUBLISHED => 'Published',
                        Chalk::STATUS_ARCHIVED  => 'Archived',
                    ],
                ),
            ),
        );
    }

    public function status($status = null)
    {
        if (isset($status)) {
            if (!isset($this->publishDate) && $status == Chalk::STATUS_PUBLISHED && $this->status != Chalk::STATUS_PUBLISHED) {
                $this->publishDate = new \Carbon\Carbon();
            } else if (!isset($this->archiveDate) && $status == Chalk::STATUS_ARCHIVED && $this->status != Chalk::STATUS_ARCHIVED) {
                $this->archiveDate = new \Carbon\Carbon();
            }
            $this->status = $status;
            return $this;
        }
        return $this->status;
    }

    public function isDraft()
    {
        return $this->status == Chalk::STATUS_DRAFT;
    }

    public function isPending()
    {
        return $this->status == Chalk::STATUS_PENDING;
    }

    public function isPublished()
    {
        return $this->status == Chalk::STATUS_PUBLISHED;
    }

    public function isArchived()
    {
        return $this->status == Chalk::STATUS_ARCHIVED;
    }

    protected function _publishable_duplicate(ChalkEntity $entity)
    {
        $entity->fromArray([
            'status'      => Chalk::STATUS_DRAFT,
            'publishDate' => null,
            'archiveDate' => null,
        ]);
    }
}