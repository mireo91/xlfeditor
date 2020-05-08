<?php
namespace Mireo\XlfEditor\Aspect;

use Mireo\XlfEditor\Domain\Repository\XlfTranslationsRepository;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Aspect
 * @Flow\Scope("singleton")
 */
class TranslateAspect{

    /**
     * @Flow\Inject
     * @var XlfTranslationsRepository
     */
    protected $xlfTranslationsRepository;

    /**
     * @Flow\Around("method(Neos\Flow\I18n\TranslationProvider\XliffTranslationProvider->getTranslationById())")
     * @param JoinPointInterface $joinPoint The current join point
     * @return void
     */
    public function generateNodeTypeSchema(JoinPointInterface $joinPoint){
        $package = $joinPoint->getMethodArgument('packageKey');
        $source = $joinPoint->getMethodArgument('sourceName');
        $id = $joinPoint->getMethodArgument('labelId');
        $locale = $joinPoint->getMethodArgument('locale');

        $key = $package.":".$source.":".$id;
        $language = $locale->getLanguage();
        $value = $this->xlfTranslationsRepository->getTranslationFor($key, $language);
        if( $value ){
            return $value;
        }
//        if( $language == 'en' && $key == 'Hb180.Reka:Components/Teaser:read_more' ){
//            return $this->xlfTranslationsRepository->getTranslationFor($key, $language);
//        }
//        $this
//        if(  ){
//            return 'test';
//        }

        return $joinPoint->getAdviceChain()->proceed($joinPoint);
    }
}
