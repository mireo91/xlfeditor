<?php
namespace Mireo\XlfEditor\TranslationMapper;

use Neos\Flow\Annotations as Flow;

class ValidationMapper implements MapperInterface {

    /**
     * @var array
     * @Flow\InjectConfiguration(package="Neos.Form", path="presets")
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $mapping;

    protected function initializeMapping(){
        foreach($this->configuration as $preset){
            if( !$preset ){
                continue;
            }
            $validatorPresets = $preset['validatorPresets']??[];
            if(!$validatorPresets){
                continue;
            }
            foreach( $validatorPresets as $validatorName=>$validatorPreset ){
                $validatorErrors = $validatorPreset['validatorErrors']??[];
                if( !$validatorErrors || !isset($validatorErrors['errors']) || !isset($validatorErrors['package']) ){
                    continue;
                }
                foreach($validatorErrors['errors'] as $name=>$code){
                    $vN = substr($validatorName, strpos($validatorName,":")+1);
                    $this->mapping[$validatorErrors['package'].":ValidationErrors:".$code] = $vN.":".$name;
                }
            }
        }
    }

    public function mapTo(string $key): string
    {
        if( !$this->mapping ){
            $this->initializeMapping();
        }
        if( isset($this->mapping[$key]) ){
            return $this->mapping[$key];
        }
        return explode(":",$key)[2];
    }

}
