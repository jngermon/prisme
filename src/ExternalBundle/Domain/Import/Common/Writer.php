<?php

namespace ExternalBundle\Domain\Import\Common;

use AppBundle\Entity\Larp;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Doctrine\ORM\EntityManager;
use ExternalBundle\Entity\ImportationProgress;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Writer extends DoctrineWriter implements InitiableWriter
{
    protected $em;

    protected $syncUuid;

    protected $optionsProcessing;

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

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function init(ImportationProgress $progress)
    {
        $progress->setName($this->entityName);

        $this->syncUuid = $progress->getUuid();
    }

    public function initProcessing($options = [])
    {
        $this->optionsProcessing = $this->createOptionsResolver()->resolve($options);
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
        $this->optionsProcessing = null;
    }

    /**
     * {@inheritdoc}
     */
    public function writeItem(array $item)
    {
        $entity = $this->findOrCreateItem($item);

        try {
            $entity->setExternalId($item['externalId'])
                ->setSyncUuid($this->syncUuid)
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
        if (empty($this->syncUuid)) {
            return;
        }

        $queryBuilder = $this->createMarkEntitiesQueryBuilder();

        $queryBuilder->getQuery()->execute();
    }

    protected function createMarkEntitiesQueryBuilder()
    {
        $queryBuilder = $this->entityRepository->createQueryBuilder('x')
            ->update()
            ->set('x.syncUuid', ':syncUuid')
            ->setParameter('syncUuid', $this->syncUuid)
            ->set('x.syncStatus', ':syncStatus')
            ->setParameter('syncStatus', Status::PENDING)
            ->andWhere('x.externalId IS NOT NULL')
            ;

        if (!empty($this->optionsProcessing['ids'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('x.externalId', $this->optionsProcessing['ids']));
        }

        return $queryBuilder;
    }

    protected function markNotUpdate()
    {
        if (empty($this->syncUuid)) {
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
            ->set('x.syncedAt', ':syncedAt')
            ->setParameter('syncedAt', new \Datetime())
            ;

        $queryBuilder->getQuery()->execute();
    }

    protected function createOptionsResolver()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'ids' => null,
        ]);

        $resolver->setNormalizer('ids', function (Options $options, $value) {
            if ($value === null) {
                return $value;
            }

            if (is_string($value)) {
                $value = intval($value);
            }

            if (!is_array($value)) {
                $value = [$value];
            }

            return $value;
        });

        $resolver->setAllowedTypes('ids', ['array', 'null', 'string', 'integer']);

        return $resolver;
    }
}
