<?php

namespace ExternalBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="external.controller.sync")
 */
class SyncController
{
    /**
     * @Route("/synchronize", name="synchronize")
     */
    public function syncAction(Request $request)
    {
        dump($request);die;
    }
}
