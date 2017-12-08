<?php

namespace ExternalBundle\Controller;

use ExternalBundle\Domain\Synchronizer\SynchronizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route(service="external.controller.sync")
 */
class SyncController
{
    protected $synchronizer;

    protected $session;

    protected $translator;

    protected $router;

    public function __construct(
        SynchronizerInterface $synchronizer,
        SessionInterface $session,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $this->synchronizer = $synchronizer;
        $this->session = $session;
        $this->translator = $translator;
        $this->router = $router;
    }

    /**
     * @Route("/synchronize", name="synchronize")
     */
    public function syncAction(Request $request)
    {
        $options = [
            'class' => $request->get('class'),
            'ids' => $request->get('ids'),
        ];

        $res = $this->synchronizer->process($options);

        if ($res->isSuccessed()) {
            $this->session->getFlashBag()->add('success', $this->translator->trans('process.successed', [], 'Sync'));
        } else {
            $this->session->getFlashBag()->add('error', $this->translator->trans('process.failed', ['%error%' => $res->getReasonPhrase()], 'Sync'));
        }

        if ($referer = $request->headers->get('referer')) {
            return new RedirectResponse($referer);
        }

        return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
    }
}
