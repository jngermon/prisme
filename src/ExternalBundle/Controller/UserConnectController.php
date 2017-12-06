<?php

namespace ExternalBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\entityManager;
use ExternalBundle\Domain\User\Connect\CredentialsType;
use ExternalBundle\Domain\User\Provider as ExternalUserProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Route(service="external.controller.user_connect")
 */
class UserConnectController
{
    protected $externalUserProvider;

    protected $templating;

    protected $formFactory;

    protected $tokenStorage;

    protected $entityManager;

    protected $router;

    public function __construct(
        ExternalUserProvider $externalUserProvider,
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage,
        EntityManager $entityManager,
        RouterInterface $router
    ) {
        $this->externalUserProvider = $externalUserProvider;
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @Route("/user-connect", name="user_connect")
     */
    public function connectAction(Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof User) {
            return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
        }

        if ($user->getPerson()) {
            return new RedirectResponse($this->router->generate('user_connected'));
        }

        $form = $this->formFactory->create(CredentialsType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $person = $form->getData()->getPerson();
            $person->setUser($user);
            $this->entityManager->persist($person);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('user_connected'));
        }

        return $this->templating->renderResponse('ExternalBundle::user/connect.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user-connected", name="user_connected")
     */
    public function connectedAction(Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user->getPerson()) {
            return new RedirectResponse($this->router->generate('user_connect'));
        }

        $externalUser = $user->getPerson()->getExternalId()
            ? $this->externalUserProvider->getUserById($user->getPerson()->getExternalId())
            : null;

        return $this->templating->renderResponse('ExternalBundle::user/connected.html.twig', [
            'externalUser' => $externalUser,
            'person' => $user->getPerson(),
        ]);
    }
}
