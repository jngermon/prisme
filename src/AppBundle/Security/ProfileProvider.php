<?php

namespace AppBundle\Security;

use AppBundle\Entity\Person;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfileProvider
{
    protected $tokenStorage;

    protected $em;

    protected $profile;

    protected $profiles;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $em
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->activeProfile = null;
        $this->profiles = null;
    }

    public function getProfiles()
    {
        if ($this->profiles === null) {
            $this->profiles = $this->loadProfiles();
        }

        return $this->profiles;
    }

    public function getActiveProfile()
    {
        if (!$this->activeProfile) {
            $this->activeProfile = $this->loadProfile();
        }

        return $this->activeProfile;
    }

    public function setActiveProfile($class, $id)
    {
        if (!$class || !$id) {
            return 'bad_request';
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user || !$user instanceof User) {
            return 'bad_user';
        }

        $person = $user->getPerson();

        if (!$person) {
            return 'user_without_person';
        }

        $profile = null;
        $relationName = '';
        foreach ($this->em->getClassMetadata(Person::class)->getAssociationMappings() as $associationMapping) {
            if (is_subclass_of($associationMapping['targetEntity'], ProfilableInterface::class) && $associationMapping['mappedBy'] && $associationMapping['targetEntity'] == $class) {

                $qb = $this->em->getRepository($associationMapping['targetEntity'])->createQueryBuilder('x')
                    ->select('x, l')
                    ->innerJoin('x.'.$associationMapping['mappedBy'], 'p')
                    ->innerJoin('x.larp', 'l')
                    ->andWhere('p = :person')
                    ->setParameter('person', $person)
                    ->andWhere('x.id = :id')
                    ->setParameter('id', $id)
                    ->setMaxResults(1)
                    ;

                $profile = $qb->getQuery()->getOneOrNullResult();
                $relationName = $associationMapping['fieldName'];
            }
        }

        if (!$profile) {
            return 'profile_not_found';
        }

        $user->setActiveProfileKey($relationName.':'.$id);
        $this->em->persist($user);
        $this->em->flush();

        return $profile;
    }

    protected function loadProfile()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user || !$user instanceof User || !$user->getActiveProfileKey()) {
            return null;
        }

        $person = $user->getPerson();

        if (!$person) {
            return null;
        }

        try {
            list($relationName, $id) = explode(':', $user->getActiveProfileKey(), 2);
        } catch (\Exception $e) {
            return null;
        }

        $profile = null;
        foreach ($this->em->getClassMetadata(Person::class)->getAssociationMappings() as $associationMapping) {
            if (is_subclass_of($associationMapping['targetEntity'], ProfilableInterface::class) && $associationMapping['mappedBy'] && $associationMapping['fieldName'] == $relationName) {

                $qb = $this->em->getRepository($associationMapping['targetEntity'])->createQueryBuilder('x')
                    ->select('x, l')
                    ->innerJoin('x.'.$associationMapping['mappedBy'], 'p')
                    ->innerJoin('x.larp', 'l')
                    ->andWhere('p = :person')
                    ->setParameter('person', $person)
                    ->andWhere('x.id = :id')
                    ->setParameter('id', $id)
                    ->setMaxResults(1)
                    ;

                $profile = $qb->getQuery()->getOneOrNullResult();
            }
        }

        return $profile;
    }

    protected function loadProfiles()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user || !$user instanceof User) {
            return null;
        }

        $person = $user->getPerson();

        if (!$person) {
            return null;
        }

        $profiles = [];
        foreach ($this->em->getClassMetadata(Person::class)->getAssociationMappings() as $associationMapping) {
            if (is_subclass_of($associationMapping['targetEntity'], ProfilableInterface::class) && $associationMapping['mappedBy']) {

                $qb = $this->em->getRepository($associationMapping['targetEntity'])->createQueryBuilder('x')
                    ->select('x, l')
                    ->innerJoin('x.'.$associationMapping['mappedBy'], 'p')
                    ->innerJoin('x.larp', 'l')
                    ->andWhere('p = :person')
                    ->setParameter('person', $person)
                    ->orderBy('l.startedAt', 'desc')
                    ;

                $profiles = array_merge(
                    $profiles,
                    $qb->getQuery()->getResult()
                );
            }
        }

        return $profiles;
    }
}
