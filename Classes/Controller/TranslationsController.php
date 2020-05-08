<?php
namespace Mireo\XlfEditor\Controller;

/*
 * This file is part of the Mireo.XlfEditor package.
 */

use Mireo\XlfEditor\Domain\XlfManager;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\View\JsonView;
use Neos\Flow\Package\PackageManager;
use Neos\Fusion\View\FusionView;
use Neos\Flow\Security\Context as SecurityContext;
use Neos\Flow\I18n\Service as LocalizationService;
use Neos\Utility\Files;

class TranslationsController extends ActionController
{

    /**
     * @var FusionView
     */
    protected $view;

    /**
     * @var string
     */
    protected $defaultViewObjectName = FusionView::class;

    /**
     * @var array
     */
    protected $supportedMediaTypes = ['application/json', 'text/html'];

    /**
     * @var array
     */
    protected $viewFormatToObjectNameMap = [
        'html' => FusionView::class,
        'json' => JsonView::class,
    ];

    /**
     * @Flow\Inject
     * @var SecurityContext
     */
    protected $securityContext;

//    /**
//     * @Flow\Inject
//     * @var Translator
//     */
//    protected $translator;

    /**
     * @Flow\Inject
     * @var LocalizationService
     */
    protected $localizationService;

    /**
     * @Flow\Inject
     * @var XlfManager
     */
    protected $xlfManager;

    protected function initializeTranslations(){

    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->initializeTranslations();

        $csrfToken = $this->securityContext->getCsrfProtectionToken();
        $flashMessages = $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush();
        $currentLocale = $this->localizationService->getConfiguration()->getCurrentLocale();

        $this->getTranslations();
//        $parsedData = [
//            'fileIdentifier' => $fileId
//        ];


//        foreach ($languages as $localeChainItem=>$preset) {
//            if( !$preset ){
//                continue;
//            }
//
////            $generalTranslationPath = $this->globalTranslationPath . $localeChainItem;
////            if (is_dir($generalTranslationPath)) {
////                $this->readDirectoryRecursively($generalTranslationPath, $parsedData, $fileId);
////            }
//        }

//        exit;

        $this->view->assignMultiple([
//            'redirectsJson' => $redirectsJson,
//            'hostOptions' => $hostOptions,
            'translationKeys' => $this->getTranslations(),
            'flashMessages' => $flashMessages,
            'csrfToken' => $csrfToken,
            'locale' => $currentLocale,
        ]);
    }

    /**
     * @param int $page
     */
    public function getTranslations($page = -1){
        $data = $this->xlfManager->getAllKeys();
        return $data;
//        $languages = $this->languagePresets['presets']??[];
//        $xliffBasePath = 'Private/Translations/';
//        $data = [];
//        // Walk locale chain in reverse, so that translations higher in the chain overwrite fallback translations
//        foreach ($this->packageManager->getFlowPackages() as $package) {
//            foreach ($languages as $localeChainItem=>$preset) {
//                if (!$preset) {
//                    continue;
//                }
//                $translationPath = $package->getResourcesPath() . $xliffBasePath . $localeChainItem;
//                if (is_dir($translationPath)) {
//                    $data[$package->getPackageKey()][$localeChainItem] = Files::readDirectoryRecursively($translationPath);
////                    \Neos\Flow\var_dump($package->getPackageKey() . '-' . $localeChainItem);
//                }
//            }
//        }
//        \Neos\Flow\var_dump($data);exit;
    }

}
