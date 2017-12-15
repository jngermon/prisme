<?php

namespace ExternalBundle\Controller;

use ExternalBundle\Entity\Enum\SynchronizationStatus;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SynchronizationController extends CRUDController
{
    public function rerunAction($id)
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }

        if (!in_array($object->getStatus(), [
            SynchronizationStatus::SUCCESSED,
            SynchronizationStatus::ERROR,
        ])) {
            throw new BadRequestHttpException('unable to rerun this synchronization');
        }

        $object->setStatus(SynchronizationStatus::PENDING);
        $em = $this->get('doctrine')->getEntityManager();
        $em->persist($object);
        $em->flush();

        exec("/srv/bin/console external:synchronize --continue >> /srv/var/logs/synchronize.log &");

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('process.persist', [], 'Sync'));

        return new RedirectResponse($this->admin->generateObjectUrl('show', $object));
    }
}
