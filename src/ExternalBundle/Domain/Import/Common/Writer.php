<?php

namespace ExternalBundle\Domain\Import\Common;

use AppBundle\Entity\Larp;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Doctrine\ORM\EntityManager;

class Writer extends DoctrineWriter
{
    protected $em;

    protected $syncUuid;

    protected $larpIdProcessing;

    protected $idsProcessing;

    public function __construct(
        EntityManager $em,
        $entityName
    ) {
        parent::__construct($em, $entityName);
        $this->disableTruncate();

        if (!is_subclass_of($entityName, SynchronizableInterface::class)) {
            throw new \RuntimeException(sprintf('The class %s doesn\'t not implements %s', $entityName, SynchronizableInterface::class));
        }

        $this->lookupFields = ['externalId'];
    }

    /**
     * @param string $syncUuid
     */
    public function setSyncUuid($syncUuid)
    {
        $this->syncUuid = $syncUuid;
        return $this;
    }

    /**
     * @param integer $larpIdProcessing
     */
    public function setLarpIdProcessing($larpIdProcessing)
    {
        $this->larpIdProcessing = $larpIdProcessing;
        return $this;
    }

    /**
     * @param array $idsProcessing
     */
    public function setIdsProcessing($idsProcessing)
    {
        $this->idsProcessing = $idsProcessing;
        return $this;
    }

    public function prepare()
    {
        parent::prepare();

        $this->markEntities();
    }

    public function finish()
    {
        parent::finish();

        $this->markNotUpdate();

        $this->syncUuid = null;
        $this->larpIdProcessing = null;
        $this->idsProcessing = null;
    }

    /**
     * {@inheritdoc}
     */
    public function writeItem(array $item)
    {
        $entity = $this->findOrCreateItem($item);

        try {
            $entity->setExternalId($item['externalId'])
                ->setSyncErrors();

            $this->loadAssociationObjectsToEntity($item, $entity);
            $this->updateEntity($item, $entity);

            $entity->setSyncStatus(Status::SYNCED);
        } catch (\Exception $e) {
            $entity->setSyncErrors($e->getMessage())
                ->setSyncStatus(Status::ERROR)
                ;
        }

        $entity->setSyncedAt(new \Datetime());

        $this->entityManager->persist($entity);
    }

    protected function markEntities()
    {
        if (empty($this->syncUuid) || (empty($this->larpIdProcessing) && empty($this->idsProcessing))) {
            return;
        }

        $queryBuilder = $this->entityRepository->createQueryBuilder('x')
            ->update()
            ->set('x.syncUuid', ':syncUuid')
            ->setParameter('syncUuid', $this->syncUuid)
            ->set('x.syncStatus', ':syncStatus')
            ->setParameter('syncStatus', Status::PENDING)
            ;

        if ($this->larpIdProcessing) {
            if ($this->entityName == Larp::class) {
                $queryBuilder->andWhere('x.externalId = :larpId');
            } else {
                $queryBuilder->innerJoin('x.larp', 'l')
                    ->andWhere('l.externalId = :larpId')
                    ;
            }

            $queryBuilder->setParameter('larpId', $this->larpIdProcessing);
        }

        if (!empty($this->idsProcessing)) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('x.externalId', ':ids'))
                ->setParameter('ids', $ids);
        }

        $queryBuilder->getQuery()->execute();
    }

    protected function markNotUpdate()
    {
        if (empty($this->syncUuid) || (empty($this->larpIdProcessing) && empty($this->idsProcessing))) {
            return;
        }

        $queryBuilder = $this->entityRepository->createQueryBuilder('x')
            ->update()
            ->andWhere('x.syncUuid = :syncUuid')
            ->setParameter('syncUuid', $this->syncUuid)
            ->andWhere('x.syncStatus = :syncStatus')
            ->setParameter('syncStatus', Status::PENDING)
            ->set('x.syncStatus', ':newSyncStatus')
            ->setParameter('newSyncStatus', Status::ERROR)
            ->set('x.syncErrors', ':error')
            ->setParameter('error', 'Not found on external')
            ;

        $queryBuilder->getQuery()->execute();
    }
}
