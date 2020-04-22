<?php
namespace Mireo\XlfEditor\Domain\Model;

/*
 * This file is part of the Mireo.XlfEditor package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class XlfTranslations
{
    /**
     * @var string
     * @ORM\Column(length=32)
     */
    protected $pathHash;

    /**
     * Absolute path of this node
     *
     * @var string
     * @ORM\Column(length=4000)
     * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=4000 })
     */
    protected $path;

    /**
     * MD5 hash of the parent path
     * This property is needed to speed up lookup by parent path.
     * The hash is generated in calculateParentPathHash().
     *
     * @var string
     * @ORM\Column(length=32)
     */
    protected $parentPathHash;
}
