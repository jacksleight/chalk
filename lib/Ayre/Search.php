<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
    Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class Search extends Model
{
    public static function parse($content)
    {
        $content = trim(preg_replace('/[^\w]+/u', ' ', $content));
        $words = explode(' ', $content);
        foreach ($words as $i => $word) {
            if (strlen($word) < 3) {
                unset($words[$i]);
            } else if (strlen($word) < 4) {
                $words[$i] = str_pad($word, 4, '_');
            }
        }
        return implode(' ', $words);
    }

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
	protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $class;

    /**
     * @ORM\Column(type="integer")
     */
    protected $class_id;
    
    protected $class_obj;

	/**
     * @ORM\Column(type="text")
     */
	protected $content;

    public function content($content = null)
    {
        if (isset($content)) {
            $this->content = self::parse($content);
        }
        return $this->content;
    }
}