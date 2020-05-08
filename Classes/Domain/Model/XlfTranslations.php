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
     * @ORM\Column(type="flow_json_array")
     * @var array<string>
     */
    protected $translations = [];

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $key
     * @return void
     */
    public function setPath($key)
    {
        $this->path = $key;
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param array $translations
     * @return void
     */
    public function setTranslations(array $translations)
    {
        $this->translations = $translations;
    }

    /**
     * @param string $language
     * @param string $translation
     * @return void
     */
    public function setTranslation($language, $translation)
    {
        $this->translations[$language] = $translation;
    }

    /**
     * @param string $language
     * @return string
     */
    public function getTranslation($language)
    {
        return $this->translations[$language]??'';
    }

}
