<?php
namespace Mireo\XlfEditor;

/*
 * This file is part of the Neos.Flow package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Cache\CacheManager;
use Neos\Flow\Monitor\FileMonitor;
use Neos\Flow\Package\Package as BasePackage;

/**
 * The Flow Package
 */
class Package extends BasePackage
{

    /**
     *
     * @param \Neos\Flow\Core\Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(\Neos\Flow\Core\Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

//        \Neos\Flow\var_dump('test');exit;

        $dispatcher->connect(FileMonitor::class, 'filesHaveChanged', function(string $fileMonitorIdentifier, array $changedFiles) use($bootstrap) {
            $cacheManager = $bootstrap->getObjectManager()->get(CacheManager::class);
            switch ($fileMonitorIdentifier) {
//                case 'Flow_ClassFiles':
//                    $this->flushClassCachesByChangedFiles($changedFiles);
//                    break;
                case 'Flow_ConfigurationFiles':
                    $nodeTypeSchemaCache = $cacheManager->getCache('Mireo_XlfEditor_Configuration');
                    $nodeTypeSchemaCache->flush();
                    break;
//                case 'Flow_TranslationFiles':
//                    $this->flushTranslationCachesByChangedFiles($changedFiles);
//                    break;
            }
        });

    }
}
