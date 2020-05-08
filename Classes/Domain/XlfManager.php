<?php
namespace Mireo\XlfEditor\Domain;

use Neos\Flow\Annotations as Flow;
use Neos\Cache\Frontend\VariableFrontend;
use Neos\Flow\I18n\Locale;
use Neos\Flow\I18n\Xliff\Service\XliffFileProvider;
use Neos\Flow\I18n\Xliff\Service\XliffReader;
use Neos\Flow\Package\PackageManager;
use Neos\Utility\Files;

class XlfManager{

    /**
     * @var string
     * @Flow\InjectConfiguration(package="Neos.ContentRepository", path="contentDimensions.language.default")
     */
    protected $defaultLanguage;

    /**
     * @Flow\Inject
     * @var PackageManager
     */
    protected $packageManager;

    /**
     * @var VariableFrontend
     */
    protected $cache;

    /**
     * @var array
     * @Flow\InjectConfiguration(package="Mireo.XlfEditor", path="configuration")
     */
    protected $configuration;

    /**
     * @Flow\Inject
     * @var XliffFileProvider
     */
    protected $xliffFileProvider;

    /**
     * @Flow\Inject
     * @var XliffReader
     */
    protected $xliffReader;

    public function getFiles(){

        $data = [];
        $xliffBasePath = 'Private/Translations/';

        foreach ($this->packageManager->getFlowPackages() as $package) {
            $translationPath = $package->getResourcesPath() . $xliffBasePath . $this->defaultLanguage;
            if (is_dir($translationPath)) {
                foreach(Files::readDirectoryRecursively($translationPath) as $path){
                    foreach( $this->configuration['matcher'] as $regex ){
                        if( preg_match($regex, $path) ){
                            $data[] = $package->getPackageKey().":".str_replace($translationPath.'/', '', substr($path,0,-4));
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getAllKeys(){
        $keys = $this->cache->get('configuration')??[];
        if( !$keys ) {
            $files = $this->getFiles();
            foreach ($files as $path) {
                $value = $this->xliffFileProvider->getMergedFileData($path, new Locale($this->defaultLanguage));
                if ($value['translationUnits']) {
                    foreach (array_keys($value['translationUnits']) as $v) {
                        $keys[] = $path . ":" . $v;
                    }
                }
            }
            $this->cache->set('configuration', $keys);
        }
        return $keys;
    }
}
