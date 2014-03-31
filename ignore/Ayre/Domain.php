<?php
namespace Ayre;

use Doctrine\ORM\Mapping as ORM,
	Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="domain")
 * @Gedmo\Tree(type="materializedPath")
 * @Gedmo\Uploadable(path="data/files", appendNumber=true, filenameGenerator="ALPHANUMERIC")
*/
class Domain extends \Ayre\Entity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @Gedmo\TreePathSource
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $name = array();

	/**
	 * @ORM\Column(type="datetime")
	 * @Gedmo\Timestampable(on="create")
	 */
	protected $createAt;

	/**
	 * @ORM\Column(type="datetime")
	 * @Gedmo\Timestampable(on="update")
	 */
	protected $updateAt;	

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Blameable(on="create")
     */
    protected $createdBy;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Blameable(on="update")
     */
    protected $updatedBy;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    protected $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\TreePath(separator="/", endsWithSeparator=false)
     */
    protected $path;

    /**
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="children")
     * @ORM\JoinColumn(name="parentId", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Domain", mappedBy="parent")
     * @ORM\OrderBy({"left"="ASC"})
     */
    protected $children;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\UploadableFilePath
     */
    protected $filePath;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\UploadableFileMimeType
     */
    protected $fileMimeType;

    /**
     * @ORM\Column(type="decimal")
     * @Gedmo\UploadableFileSize
     */
    protected $fileSize;
}