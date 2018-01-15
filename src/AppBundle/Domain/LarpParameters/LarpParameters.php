<?php

namespace AppBundle\Domain\LarpParameters;

use AppBundle\Entity\Larp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class LarpParameters
{
    /**
     * @Groups({"export"})
     */
    protected $calendars;

    /**
     * @Groups({"export"})
     */
    protected $characterDataDefinitionEnumCategories;

    /**
     * @Groups({"export"})
     */
    protected $characterDataSections;

    public function __construct()
    {
        $this->calendars = [];
        $this->characterDataDefinitionEnumCategories = [];
        $this->characterDataSections = [];
    }

    public function load(Larp $larp)
    {
        $this->setCalendars($larp->getCalendars());
        $this->setCharacterDataDefinitionEnumCategories($larp->getCharacterDataDefinitionEnumCategories());
        $this->setCharacterDataSections($larp->getCharacterDataSections());
    }

    public function applyPersist(EntityManagerInterface $em)
    {
        foreach ($this->getCalendars() as $calendar) {
            $em->persist($calendar);
        }

        foreach ($this->getCharacterDataDefinitionEnumCategories() as $category) {
            $em->persist($category);
        }

        foreach ($this->getCharacterDataSections() as $section) {
            $em->persist($section);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getCalendars()
    {
        return $this->calendars;
    }

    /**
     * @param ArrayCollection $calendars
     */
    public function setCalendars($calendars)
    {
        $this->calendars = $calendars;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCharacterDataDefinitionEnumCategories()
    {
        return $this->characterDataDefinitionEnumCategories;
    }

    /**
     * @param ArrayCollection $characterDataDefinitionEnumCategories
     */
    public function setCharacterDataDefinitionEnumCategories($characterDataDefinitionEnumCategories)
    {
        $this->characterDataDefinitionEnumCategories = $characterDataDefinitionEnumCategories;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCharacterDataSections()
    {
        return $this->characterDataSections;
    }

    /**
     * @param ArrayCollection $characterDataSections
     */
    public function setCharacterDataSections($characterDataSections)
    {
        $this->characterDataSections = $characterDataSections;

        return $this;
    }
}
