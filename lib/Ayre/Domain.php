<?php
namespace Ayre;

use Doctrine\ORM\Mapping as ORM,
	Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="domain")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="_class", type="string")
*/
class Domain extends \Ayre\Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name = array();
}

/**
 * @ORM\Entity
*/
class Potato extends Domain
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $description = array();
}

/**
 * @ORM\Entity
*/
class SweetPotato extends Potato
{}

/**
 * @ORM\Entity
*/
class RedPotato extends Potato
{}
