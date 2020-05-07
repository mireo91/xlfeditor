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
    protected $path;

    /**
     * Absolute path of this node
     *
     * @var string
     * @ORM\Column(type="flow_json_array")
     * @var array<string>
     */
    protected $translations = [];

}
