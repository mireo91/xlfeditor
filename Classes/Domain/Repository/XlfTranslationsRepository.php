<?php
namespace Mireo\XlfEditor\Domain\Repository;

/*
 * This file is part of the Mireo.XlfEditor package.
 */

use Mireo\XlfEditor\Domain\Model\XlfTranslations;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class XlfTranslationsRepository extends Repository
{

    public function getTranslationFor($id, $lang){
        $query = $this->createQuery();

        $objects = $query->matching(
            $query->equals('path', $id)
        )->execute();

        if( !count($objects) ){
            return null;
        }
        /** @var XlfTranslations $object */
        $object = $objects[0];

        return $object->getTranslation($lang);
    }

    // add customized methods here
}
