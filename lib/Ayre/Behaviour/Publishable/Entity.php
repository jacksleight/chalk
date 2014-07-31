<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Publishable;

use Ayre;

trait Entity
{
	/**
     * @Column(type="string", length=10)
     */
	protected $status = Ayre::STATUS_DRAFT;

    /**
     * @Column(type="datetime", nullable=true)
     */
	protected $publishDate;

    /**
     * @Column(type="datetime", nullable=true)
     */
	protected $archiveDate;

    protected static function _defineMetadata($class)
    {
        return array(
            'fields' => array(
                'status' => array(
                    'values' => [
                        Ayre::STATUS_DRAFT     => 'Draft',
                        Ayre::STATUS_PUBLISHED => 'Published',
                        Ayre::STATUS_ARCHIVED  => 'Archived',
                    ],
                ),
            ),
        );
    }

    public function status($status = null)
    {
        if (isset($status)) {
            if (!isset($this->publishDate) && $status == Ayre::STATUS_PUBLISHED && $this->status != Ayre::STATUS_PUBLISHED) {
                $this->publishDate = new \Carbon\Carbon();
            } else if (!isset($this->archiveDate) && $status == Ayre::STATUS_ARCHIVED && $this->status != Ayre::STATUS_ARCHIVED) {
                $this->archiveDate = new \Carbon\Carbon();
            }
            $this->status = $status;
            return $this;
        }
        return $this->status;
    }
}