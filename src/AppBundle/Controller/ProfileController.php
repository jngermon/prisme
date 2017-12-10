<?php

namespace AppBundle\Controller;

use AppBundle\Security\ProfilableInterface;
use AppBundle\Security\ProfileProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/profile")
 */
class ProfileController
{
    protected $provider;

    protected $router;

    protected $session;

    protected $translator;

    public function __construct(
        ProfileProvider $provider,
        RouterInterface $router,
        SessionInterface $session,
        TranslatorInterface $translator
    ) {
        $this->provider = $provider;
        $this->router = $router;
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * @Route("/change-active", name="profile.change_active")
     */
    public function changeActiveProfile(Request $request)
    {
        $class = $request->get('class');
        $id = $request->get('id');

        $res = $this->provider->setActiveProfile($class, $id);

        if ($res instanceof ProfilableInterface) {
            $this->session->getFlashBag()->add('success', $this->translator->trans('change.success', [], 'Profile'));
        } else {
            $this->session->getFlashBag()->add('error', $this->translator->trans('change.'.$res, [], 'Profile'));
        }

        return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
    }
}
