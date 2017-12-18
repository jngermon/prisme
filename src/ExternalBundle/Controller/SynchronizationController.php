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
            SynchronizationStatus::ABORTED,
        ])) {
            throw new BadRequestHttpException('unable to rerun this synchronization');
        }

        $object->setStatus(SynchronizationStatus::PENDING);
        $em = $this->get('doctrine')->getEntityManager();
        $em->persist($object);

        foreach ($object->getImportations() as $importation) {
            $em->remove($importation);
        }

        $em->flush();

        $this->get('external.domain.executor')->run();

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('process.rerun', [], 'Synchronization'));

        return new RedirectResponse($this->admin->generateObjectUrl('show', $object));
    }

    public function stopAction($id)
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }

        if ($object->getStatus() != SynchronizationStatus::PROCESSING) {
            throw new BadRequestHttpException('unable to stop not processing synchronization');
        }

        if ($object->getPid() && $object->getCommand()) {
            $this->get('external.domain.executor')->stop($object->getPid(), $object->getCommand());
        }

        $object->setStatus(SynchronizationStatus::ABORTED);
        $em = $this->get('doctrine')->getEntityManager();
        $em->persist($object);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('process.stop', [], 'Synchronization'));

        return new RedirectResponse($this->admin->generateObjectUrl('show', $object));
    }
}
