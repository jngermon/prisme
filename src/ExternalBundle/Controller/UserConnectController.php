<?php

namespace ExternalBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Entity\User;
use Doctrine\ORM\entityManager;
use ExternalBundle\Domain\User\Connect\CredentialsType;
use ExternalBundle\Domain\User\Provider as ExternalUserProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("is_granted('ROLE_APP_ADMIN_PERSON_CREATE')")
     */
    public function connectAction(Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof User) {
            return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
        }

        if ($user->getPerson()) {
            return new RedirectResponse($this->getRouteToPerson($user->getPerson()));
        }

        $form = $this->formFactory->create(CredentialsType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Reload user from database
            $user = $this->entityManager->getRepository(User::class)->findOneById($user->getId());

            $person = $form->getData()->getPerson();
            $person->setUser($user);
            $this->entityManager->persist($person);
            $this->entityManager->flush();

            return new RedirectResponse($this->getRouteToPerson($person));
        }

        return $this->templating->renderResponse('ExternalBundle::user/connect.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    protected function getRouteToPerson(Person $person)
    {
        return $this->router->generate('app_admin_person_show', ['id' => $person->getId()]);
    }
}
