<?php
namespace Mireo\XlfEditor\Controller;

/*
 * This file is part of the Mireo.XlfEditor package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

class TranslationsController extends ActionController
{

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('foos', array(
            'bar', 'baz'
        ));
    }
}
