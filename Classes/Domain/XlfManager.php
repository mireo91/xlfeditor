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

    /**
     * @param $tabName
     * @return array
     */
    public function getFiles($tabName){

        $data = [];
        $xliffBasePath = 'Private/Translations/';

        foreach ($this->packageManager->getFlowPackages() as $package) {
            $translationPath = $package->getResourcesPath() . $xliffBasePath . $this->defaultLanguage;
            if (is_dir($translationPath)) {
                foreach(Files::readDirectoryRecursively($translationPath) as $path){
                    foreach( $this->configuration['tabs'][$tabName]['matcher'] as $regex ){
                        if( preg_match($regex, $path) ){
                            $key = $package->getPackageKey().":".str_replace($translationPath.'/', '', substr($path,0,-4));
                            $data[] = $key ;
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getAllKeys( $tabName = null ){
        if(!$tabName){
            $tabName = $this->configuration['defaultTab'];
        }
        $keys = $this->cache->get('configuration_'.$tabName)??[];
        if( !$keys ) {
            $keyMatcherObjectName = $this->configuration['tabs'][$tabName]['keyMatcherObjectName']??'Mireo\XlfEditor\TranslationMapper\ValidationMapper';
            $matcherObject = new $keyMatcherObjectName;
            $files = $this->getFiles($tabName);
            foreach ($files as $path) {
                $explodedPath = explode(":", $path);
                $value = $this->xliffFileProvider->getMergedFileData($path, new Locale($this->defaultLanguage));
                if ($value['translationUnits']) {
                    foreach (array_keys($value['translationUnits']) as $v) {
                        $key = $path . ":" . $v;
                        $label = $matcherObject->mapTo($key);
                        $keys[$explodedPath[0]][$explodedPath[1]][] = [
                            'originalKey' => $key,
                            'label' => $label
                        ];
                    }
                }
            }
            $this->cache->set('configuration_'.$tabName, $keys);
        }
        return $keys;
    }
}
