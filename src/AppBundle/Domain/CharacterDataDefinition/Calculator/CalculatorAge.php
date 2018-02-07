<?php

namespace AppBundle\Domain\CharacterDataDefinition\Calculator;

use AppBundle\Domain\Calendar\Model\Date;
use AppBundle\Entity\CharacterDataDefinition;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CalculatorAge extends BaseCalculator
{
    protected $router;

    protected $translator;

    public function __construct(
        RouterInterface $router,
        TranslatorInterface $translator
    ) {
        $this->router = $router;
        $this->translator = $translator;
    }

    public function getProcessorName()
    {
        return 'age';
    }

    public function getMapping()
    {
        return [
            'birthdate',
        ];
    }

    protected function doProcess($request)
    {
        $character = $request['character'];
        $definition = $request['definition'];
        $date = $character->getData($definition->getMapping()['birthdate']);

        if (!$date || !$date instanceof Date) {
            return '';
        }

        return $this->getAgeFor($date);
    }

    public function getFormOptions(CharacterDataDefinition $definition)
    {
        return [
            'attr' => [
                'data-remote' => $this->router->generate('calculator.age'),
            ],
        ];
    }

    public function getAgeFor(Date $date)
    {
        if (!$date->getCalendar()) {
            return '';
        }

        $diff = $date->getCalendar()->getLarp()->getInGameDate()->getNbDaysFromOrigin() - $date->getNbDaysFromOrigin();

        if ($diff < 0) {
            return '';
        }

        $age = floor($diff / $date->getCalendar()->getNbDays());

        return $this->translator->transchoice('format', $age, ['%age%' => $age], 'CharacterDataDefinitionCalculatorAge');
    }
}
